<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffOrderController extends Controller
{
    private function authorizeStaffAccess(): void
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'], true)) {
            abort(403);
        }
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
            ->with(['customer', 'service']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('service_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('full_name', 'like', "%{$search}%");
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
            'readyCount' => $statusCounts->get('Ready for Pickup', 0),
        ]);
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

        return view('staff.orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        $this->authorizeStaffAccess();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'service_id' => ['required', 'exists:services,id'],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $customer = $this->findOrCreateCustomer(
                $validated['full_name'],
                $validated['contact_number'] ?? null
            );

            $service = Service::findOrFail($validated['service_id']);

            return LaundryOrder::create([
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'service_number' => $this->nextServiceNumber(),
                'kilos' => null,
                'load_count' => 0,
                'detergent_quantity' => 0,
                'fabric_conditioner_quantity' => 0,
                'total_amount' => 0,
                'status' => 'Received',
                'received_at' => now(),
                'completed_at' => null,
            ]);
        });

        return redirect()
            ->route('staff.orders.edit', ['order' => $order->id])
            ->with('success', 'Laundry order created. Enter the actual weight to continue.');
    }

    public function edit(LaundryOrder $order)
    {
        $this->authorizeStaffAccess();

        $order->load(['customer', 'service']);

        return view('staff.orders.edit', compact('order'));
    }

    public function update(Request $request, LaundryOrder $order)
    {
        $this->authorizeStaffAccess();

        $validated = $request->validate([
            'kilos' => ['required', 'numeric', 'min:0.01'],
            'status' => [
                'required',
                'in:Received,Washing,Drying,Folding,Ready for Pickup,Claimed',
            ],
        ]);

        $order->load('service');

        $kilos = (float) $validated['kilos'];
        $loadCount = (int) ceil($kilos / 8.50);
        $totalAmount = $loadCount * (float) $order->service->price;

        $order->update([
            'kilos' => $kilos,
            'load_count' => $loadCount,
            'total_amount' => $totalAmount,
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'Claimed'
                ? ($order->completed_at ?? now())
                : null,
        ]);

        return redirect()
            ->route('staff.orders.index')
            ->with('success', 'Laundry order updated successfully.');
    }

    private function findOrCreateCustomer(
        string $fullName,
        ?string $contactNumber
    ): Customer {
        $query = Customer::query()
            ->where('full_name', $fullName);

        if ($contactNumber) {
            $query->where('contact_number', $contactNumber);
        } else {
            $query->whereNull('contact_number');
        }

        return $query->first() ?? Customer::create([
            'customer_code' => (string) ((Customer::query()->max('id') ?? 100) + 1),
            'full_name' => $fullName,
            'contact_number' => $contactNumber,
        ]);
    }

    private function nextServiceNumber(): string
    {
        return 'STF-' . str_pad(
            (string) ((LaundryOrder::query()->max('id') ?? 0) + 1),
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}