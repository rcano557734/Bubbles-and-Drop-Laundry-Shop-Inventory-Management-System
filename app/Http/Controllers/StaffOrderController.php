<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\Machine;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StaffOrderController extends Controller
{
    private const ACTIVE_MACHINE_STATUSES = [
        'Washing',
        'Drying',
    ];

    private const STATUSES = [
        'Received',
        'Washing',
        'Drying',
        'Folding',
        'Ready for Pickup',
        'Claimed',
    ];

    private function authorizeStaffAccess(): void
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'], true)) {
            abort(403);
        }
    }

    private function getNextStatus(string $currentStatus): ?string
    {
        return match ($currentStatus) {
            'Received' => 'Washing',
            'Washing' => 'Drying',
            'Drying' => 'Folding',
            'Folding' => 'Ready for Pickup',
            'Ready for Pickup' => 'Claimed',
            'Claimed' => null,
            default => null,
        };
    }

    /**
     * Find the first physical machine whose TOP/WASHING
     * section is free.
     *
     * The BOTTOM/DRYING section is independent.
     */
    private function findAvailableWashingMachines(
        int $requiredCount,
        int $exceptOrderId
    ) {
        $machines = Machine::query()
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        if ($machines->isEmpty()) {
            throw ValidationException::withMessages([
                'status' =>
                    'No machines have been configured in the system. Please add the 4 physical machines first.',
            ]);
        }

        $availableMachines = collect();

        foreach ($machines as $machine) {

            /*
            * Only the TOP/WASHING section matters here.
            *
            * A machine is considered unavailable for washing only
            * when another order is currently Washing on that machine.
            */
            $topOccupied = LaundryOrder::query()
                ->where('id', '!=', $exceptOrderId)
                ->where('status', 'Washing')
                ->whereHas('machines', function ($query) use ($machine) {
                    $query->where(
                        'machines.id',
                        $machine->id
                    );
                })
                ->exists();

            if (!$topOccupied) {
                $availableMachines->push($machine);
            }

            if ($availableMachines->count() >= $requiredCount) {
                break;
            }
        }

        if ($availableMachines->count() < $requiredCount) {
            throw ValidationException::withMessages([
                'status' =>
                    "This laundry requires {$requiredCount} washing sections, but only {$availableMachines->count()} are currently available.",
            ]);
        }

        return $availableMachines;
    }

    /**
     * Check whether the BOTTOM/DRYING section of a machine
     * is occupied.
     */
    private function hasDryingOrder(
        int $machineId,
        int $exceptOrderId
    ): bool {
        return LaundryOrder::query()
            ->where('id', '!=', $exceptOrderId)
            ->where('status', 'Drying')
            ->whereHas('machines', function ($query) use ($machineId) {
                $query->where(
                    'machines.id',
                    $machineId
                );
            })
            ->exists();
    }

    /**
     * Synchronize the overall machine status.
     *
     * A machine is:
     * Available = neither TOP nor BOTTOM occupied
     * In Use    = at least one section occupied
     */
    private function syncMachineStatus(int $machineId): void
    {
        $inUse = LaundryOrder::query()
            ->whereIn(
                'status',
                self::ACTIVE_MACHINE_STATUSES
            )
            ->whereHas('machines', function ($query) use ($machineId) {
                $query->where(
                    'machines.id',
                    $machineId
                );
            })
            ->exists();

        Machine::query()
            ->where('id', $machineId)
            ->update([
                'status' => $inUse
                    ? 'In Use'
                    : 'Available',
            ]);
    }

    public function index(Request $request)
    {
        $this->authorizeStaffAccess();

        $search = trim($request->query('search', ''));
        $status = $request->query('status', 'All');

        $baseQuery = LaundryOrder::query()
            ->where('status', '!=', 'Claimed');

        $activeOrders = (clone $baseQuery)->get();

        $statusCounts = $activeOrders
            ->groupBy('status')
            ->map(fn ($orders) => $orders->count());

        $query = (clone $baseQuery)
            ->with([
                'customer',
                'service',
                'machine',
                'machines',
            ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where(
                    'service_number',
                    'like',
                    "%{$search}%"
                )->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where(
                        'full_name',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        if ($status !== 'All') {
            $query->where('status', $status);
        }

        $orders = $query
            ->latest()
            ->get();

        return view('staff.orders.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
            'statusCounts' => $statusCounts,
            'totalActive' => $activeOrders->count(),
            'readyCount' => $statusCounts->get(
                'Ready for Pickup',
                0
            ),
        ]);
    }

    public function history(Request $request)
    {
        $this->authorizeStaffAccess();

        $search = trim($request->query('search', ''));

        $query = LaundryOrder::query()
            ->with([
                'customer',
                'service',
                'machine',
                'machines',
            ])
            ->where('status', 'Claimed');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where(
                    'service_number',
                    'like',
                    "%{$search}%"
                )->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where(
                        'full_name',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        $orders = $query
            ->latest('completed_at')
            ->latest()
            ->get();

        return view(
            'staff.orders.history',
            compact('orders', 'search')
        );
    }

    public function create()
    {
        $this->authorizeStaffAccess();

        $services = Service::query()
            ->whereIn('service_name', [
                'Wash, Dry, and Fold',
                'Self Service',
            ])
            ->orderBy('service_name')
            ->get();

        return view(
            'staff.orders.create',
            compact('services')
        );
    }

    public function store(Request $request)
    {
        $this->authorizeStaffAccess();

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'service_id' => [
                'required',
                'exists:services,id',
            ],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $customer = $this->findOrCreateCustomer(
                $validated['full_name'],
                $validated['contact_number'] ?? null
            );

            $service = Service::findOrFail(
                $validated['service_id']
            );

            $initialTotal =
                $service->service_name === 'Self Service'
                    ? 100
                    : 0;

            return LaundryOrder::create([
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'order_source' => 'staff',

                'machine_id' => null,

                'service_number' =>
                    $this->nextServiceNumber(),

                'kilos' => null,
                'load_count' => 0,

                'detergent_quantity' => 0,
                'fabric_conditioner_quantity' => 0,

                'total_amount' => $initialTotal,

                'status' => 'Received',

                'payment_status' => 'Unpaid',
                'paid_at' => null,

                'received_at' => now(),
                'completed_at' => null,
            ]);
        });

        return redirect()
            ->route(
                'staff.orders.edit',
                ['order' => $order->id]
            )
            ->with(
                'success',
                'Laundry order created. Enter the required details to continue.'
            );
    }

    public function edit(LaundryOrder $order)
    {
        $this->authorizeStaffAccess();

        $order->load([
            'customer',
            'service',
            'machine',
        ]);

        $detergentService = Service::query()
            ->where(
                'service_name',
                'Detergent'
            )
            ->first();

        $conditionerService = Service::query()
            ->where(
                'service_name',
                'Fabric Conditioner'
            )
            ->first();

        $detergentPrice = (float) (
            $detergentService?->price ?? 10
        );

        $conditionerPrice = (float) (
            $conditionerService?->price ?? 15
        );

        return view(
            'staff.orders.edit',
            compact(
                'order',
                'detergentPrice',
                'conditionerPrice'
            )
        );
    }

    public function update(
        Request $request,
        LaundryOrder $order
    ) {
        $this->authorizeStaffAccess();

        if ($order->status === 'Claimed') {
            throw ValidationException::withMessages([
                'status' =>
                    'A claimed order can only be restored using Undo Claim.',
            ]);
        }

        $currentStatus = $order->status;

        $validated = $request->validate([
            'status' => [
                'required',
                'in:' . implode(',', self::STATUSES),
            ],

            'payment_status' => [
                'required',
                'in:Unpaid,Paid',
            ],

            'kilos' => [
                'nullable',
                'numeric',
                'min:0.01',
            ],
        ]);

        $newStatus = $validated['status'];

        /*
         * Only allow one step forward.
         */
        if ($newStatus !== $currentStatus) {
            $allowedNextStatus =
                $this->getNextStatus($currentStatus);

            if ($newStatus !== $allowedNextStatus) {
                throw ValidationException::withMessages([
                    'status' =>
                        "The order must move from {$currentStatus} to {$allowedNextStatus}.",
                ]);
            }
        }

        /*
         * A laundry must be Paid before Claim.
         */
        if (
            $newStatus === 'Claimed' &&
            $validated['payment_status'] !== 'Paid'
        ) {
            throw ValidationException::withMessages([
                'payment_status' =>
                    'The laundry must be marked as Paid before it can be Claimed.',
            ]);
        }

        DB::transaction(function () use (
            $order,
            $currentStatus,
            $newStatus,
            $validated
        ) {
            $order->load([
                'service',
                'machine',
            ]);

            $isSelfService =
                $order->service->service_name === 'Self Service';

            $currentMachineId = $order->machine_id;

            $assignedMachineIds = $order
                ->machines()
                ->pluck('machines.id')
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            /*
             * ---------------------------------------------
             * PAYMENT
             * ---------------------------------------------
             */
            $updates = [
                'status' =>
                    $newStatus,

                'payment_status' =>
                    $validated['payment_status'],

                'paid_at' =>
                    $validated['payment_status'] === 'Paid'
                        ? ($order->paid_at ?? now())
                        : null,
            ];


            /*
             * ---------------------------------------------
             * RECEIVED
             * ---------------------------------------------
             *
             * WDF requires weight.
             *
             * Self Service does NOT require weight because
             * the price is a flat ₱100 regardless of kg/load.
             */
            if ($currentStatus === 'Received') {

                if (!$isSelfService) {

                    if (
                        !array_key_exists(
                            'kilos',
                            $validated
                        ) ||
                        $validated['kilos'] === null
                    ) {
                        throw ValidationException::withMessages([
                            'kilos' =>
                                'Please enter the actual laundry weight before starting Wash, Dry, and Fold.',
                        ]);
                    }

                    $kilos = (float) $validated['kilos'];

                    $loadCount = (int) ceil(
                        $kilos / 8.50
                    );

                    $requiredMachineCount = $isSelfService
                        ? 1
                        : $loadCount;

                    $totalAmount =
                        $loadCount
                        * (float) $order->service->price;

                    $updates['kilos'] =
                        $kilos;

                    $updates['load_count'] =
                        $loadCount;

                    $updates['detergent_quantity'] =
                        0;

                    $updates['fabric_conditioner_quantity'] =
                        0;

                    $updates['total_amount'] =
                        $totalAmount;

                } else {

                    /*
                     * Self Service:
                     *
                     * No weight required.
                     * No load-based pricing.
                     *
                     * Keep the detergent and conditioner
                     * choices saved by the customer.
                     */
                    $detergentQuantity =
                        (int) (
                            $order->detergent_quantity
                            ?? 0
                        );

                    $conditionerQuantity =
                        (int) (
                            $order->fabric_conditioner_quantity
                            ?? 0
                        );

                    $detergentService =
                        Service::query()
                            ->where(
                                'service_name',
                                'Detergent'
                            )
                            ->first();

                    $conditionerService =
                        Service::query()
                            ->where(
                                'service_name',
                                'Fabric Conditioner'
                            )
                            ->first();

                    $detergentPrice =
                        (float) (
                            $detergentService?->price
                            ?? 10
                        );

                    $conditionerPrice =
                        (float) (
                            $conditionerService?->price
                            ?? 15
                        );

                    $totalAmount =
                        100
                        + (
                            $detergentQuantity
                            * $detergentPrice
                        )
                        + (
                            $conditionerQuantity
                            * $conditionerPrice
                        );

                    $updates['kilos'] =
                        null;

                    $updates['load_count'] =
                        0;

                    $updates['total_amount'] =
                        $totalAmount;
                }
            }


            /*
             * ---------------------------------------------
             * RECEIVED → WASHING
             * ---------------------------------------------
             *
             * Automatically assign a TOP/WASHING section.
             */
            if (
                $currentStatus === 'Received' &&
                $newStatus === 'Washing'
            ) {
                /*
                * WDF:
                * 1 load = 1 washing section
                * 2 loads = 2 washing sections
                * 3 loads = 3 washing sections
                * 4 loads = 4 washing sections
                *
                * Self Service = 1 washing section.
                */
                $requiredMachineCount = $isSelfService
                    ? 1
                    : max(
                        1,
                        (int) ($updates['load_count'] ?? 1)
                    );

                $availableMachines =
                    $this->findAvailableWashingMachines(
                        $requiredMachineCount,
                        $order->id
                    );

                $machineIds = $availableMachines
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->all();

                $currentMachineId =
                    $machineIds[0];

                $assignedMachineIds =
                    $machineIds;

                $updates['machine_id'] =
                    $currentMachineId;

                /*
                * Store ALL machines assigned to this laundry.
                */
                $order->machines()->sync(
                    $machineIds
                );
            }


            /*
             * ---------------------------------------------
             * WASHING → DRYING
             * ---------------------------------------------
             *
             * Same physical machine.
             *
             * The laundry moves:
             *
             * TOP    → BOTTOM
             *
             * The TOP then becomes available to another order.
             */
            if (
                $currentStatus === 'Washing' &&
                $newStatus === 'Drying'
            ) {
                $assignedMachines = $order
                    ->machines()
                    ->lockForUpdate()
                    ->get();

                if ($assignedMachines->isEmpty()) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'This laundry does not have any machines assigned.',
                    ]);
                }

                $assignedMachineIds = $assignedMachines
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->all();

                foreach ($assignedMachines as $machine) {
                    $dryingOccupied =
                        $this->hasDryingOrder(
                            (int) $machine->id,
                            $order->id
                        );

                    if ($dryingOccupied) {
                        throw ValidationException::withMessages([
                            'status' =>
                                "{$machine->machine_name}'s drying section is currently occupied.",
                        ]);
                    }
                }

                $currentMachineId =
                    (int) $assignedMachines->first()->id;

                $updates['machine_id'] =
                    $currentMachineId;
            }


            /*
             * ---------------------------------------------
             * CLAIM
             * ---------------------------------------------
             */
            if ($newStatus === 'Claimed') {
                $updates['completed_at'] =
                    $order->completed_at ?? now();
            }


            /*
             * Save the order first.
             */
            $order->update($updates);


            /*
             * Keep exactly ONE machine in the old pivot relation
             * for compatibility with the current index/history views.
             *
             * This does NOT control availability.
             *
             * The real occupancy is based on:
             * machine_id + status.
             */


            /*
             * Make the physical machine status accurate.
             *
             * Important:
             * When Washing → Drying happens, the machine remains
             * In Use because the BOTTOM is occupied.
             *
             * However, the TOP is now available because the order
             * itself is no longer Washing.
             */
            foreach ($assignedMachineIds as $machineId) {
                $this->syncMachineStatus($machineId);
            }
            });

        return redirect()
            ->route('staff.orders.index')
            ->with(
                'success',
                'Laundry order updated successfully.'
            );
    }

    public function undoClaim(LaundryOrder $order)
    {
        $this->authorizeStaffAccess();

        if ($order->status !== 'Claimed') {
            return redirect()
                ->route('staff.orders.history')
                ->with(
                    'error',
                    'This laundry order is not currently claimed.'
                );
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'Ready for Pickup',
                'completed_at' => null,
            ]);

            /*
             * No machine is assigned again.
             *
             * The laundry has already left the machine.
             */
        });

        return redirect()
            ->route('staff.orders.history')
            ->with(
                'success',
                'Laundry claim was undone. The order is now Ready for Pickup.'
            );
    }

    private function findOrCreateCustomer(
        string $fullName,
        ?string $contactNumber
    ): Customer {
        $query = Customer::query()
            ->where(
                'full_name',
                $fullName
            );

        if ($contactNumber) {
            $query->where(
                'contact_number',
                $contactNumber
            );
        } else {
            $query->whereNull(
                'contact_number'
            );
        }

        return $query->first()
            ?? Customer::create([
                'customer_code' => (string) (
                    (
                        Customer::query()->max('id')
                        ?? 100
                    ) + 1
                ),

                'full_name' =>
                    $fullName,

                'contact_number' =>
                    $contactNumber,
            ]);
    }

    private function nextServiceNumber(): string
    {
        return 'STF-' . str_pad(
            (string) (
                (
                    LaundryOrder::query()->max('id')
                    ?? 0
                ) + 1
            ),
            5,
            '0',
            STR_PAD_LEFT
        );
    }

    public function customerNotifications()
    {
        $this->authorizeStaffAccess();

        $orders = LaundryOrder::query()
            ->with([
                'customer',
                'service',
            ])
            ->where(
                'order_source',
                'customer'
            )
            ->where(
                'status',
                'Received'
            )
            ->latest('created_at')
            ->get();

        $latest = $orders->first();

        return response()->json([
            'count' =>
                $orders->count(),

            'latest' => $latest
                ? [
                    'id' =>
                        $latest->id,

                    'service_number' =>
                        $latest->service_number,

                    'customer_name' =>
                        $latest->customer->full_name,

                    'service_name' =>
                        $latest->service->service_name,

                    'created_at' =>
                        optional(
                            $latest->created_at
                        )->toIso8601String(),
                ]
                : null,
        ]);
    }
}