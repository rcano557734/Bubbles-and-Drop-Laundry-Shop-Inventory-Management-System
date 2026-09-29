<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\Service;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function showCheckForm()
    {
        return view('customer.check-laundry');
    }

    public function searchLaundry(Request $request)
    {
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $customerQuery = Customer::where(
            'customer_code',
            $validated['customer_code']
        )->where(
            'full_name',
            $validated['full_name']
        );

        if (!empty($validated['contact_number'])) {
            $customerQuery->where(
                'contact_number',
                $validated['contact_number']
            );
        }

        $customer = $customerQuery->first();

        if (!$customer) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Customer information could not be verified.'
                );
        }

        $order = $customer->laundryOrders()
            ->with('service')
            ->where('status', '!=', 'Claimed')
            ->latest()
            ->first();

        if (!$order) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'No active laundry order was found for this customer.'
                );
        }

        return view(
            'customer.laundry-status',
            compact('customer', 'order')
        );
    }

    public function showAvailService()
    {
        $services = Service::whereIn('service_name', [
            'Self Service',
            'Wash, Dry, and Fold',
        ])->get();

        $detergentService = Service::where(
            'service_name',
            'Detergent'
        )->first();

        $conditionerService = Service::where(
            'service_name',
            'Fabric Conditioner'
        )->first();

        $detergentPrice = (float) (
            $detergentService?->price ?? 10
        );

        $conditionerPrice = (float) (
            $conditionerService?->price ?? 15
        );

        return view(
            'customer.avail-service',
            compact(
                'services',
                'detergentPrice',
                'conditionerPrice'
            )
        );
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'service_id' => [
                'required',
                'exists:services,id',
            ],

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

            'detergent_quantity' => [
                'nullable',
                'integer',
                'min:0',
                'max:1',
            ],

            'fabric_conditioner_quantity' => [
                'nullable',
                'integer',
                'min:0',
                'max:1',
            ],
        ]);

        $order = \Illuminate\Support\Facades\DB::transaction(
            function () use ($validated) {

                /*
                * Find existing customer.
                */
                $customerQuery = Customer::query()
                    ->where(
                        'full_name',
                        $validated['full_name']
                    );

                if (!empty($validated['contact_number'])) {
                    $customerQuery->where(
                        'contact_number',
                        $validated['contact_number']
                    );
                }

                $customer = $customerQuery->first();


                /*
                * Create customer if not found.
                */
                if (!$customer) {

                    $lastCustomer =
                        Customer::query()
                            ->orderByDesc('id')
                            ->first();

                    $nextCode = $lastCustomer
                        ? ((int) $lastCustomer->customer_code + 1)
                        : 101;

                    $customer = Customer::create([
                        'customer_code' =>
                            (string) $nextCode,

                        'full_name' =>
                            $validated['full_name'],

                        'contact_number' =>
                            $validated['contact_number'] ?? null,
                    ]);
                }


                /*
                * Prevent another active order for this customer.
                */
                $existingOrder = $customer
                    ->laundryOrders()
                    ->where(
                        'status',
                        '!=',
                        'Claimed'
                    )
                    ->latest()
                    ->first();

                if ($existingOrder) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'full_name' =>
                            "This customer already has an active laundry order ({$existingOrder->service_number}). Please wait until it is claimed before creating another order.",
                    ]);
                }


                /*
                * Get selected service.
                */
                $service = Service::findOrFail(
                    $validated['service_id']
                );

                $isSelfService =
                    $service->service_name === 'Self Service';


                /*
                * Self Service add-ons.
                */
                $detergentQuantity = $isSelfService
                    ? (int) (
                        $validated['detergent_quantity'] ?? 0
                    )
                    : 0;

                $conditionerQuantity = $isSelfService
                    ? (int) (
                        $validated['fabric_conditioner_quantity']
                        ?? 0
                    )
                    : 0;


                /*
                * Get add-on prices.
                */
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
                        $detergentService?->price ?? 10
                    );

                $conditionerPrice =
                    (float) (
                        $conditionerService?->price ?? 15
                    );


                /*
                * Initial total.
                */
                if ($isSelfService) {

                    $initialTotal =
                        100
                        + (
                            $detergentQuantity
                            * $detergentPrice
                        )
                        + (
                            $conditionerQuantity
                            * $conditionerPrice
                        );

                } else {

                    $initialTotal = 0;
                }


                /*
                * Generate service number.
                */
                $servicePrefix =
                    $service->service_name === 'Wash, Dry, and Fold'
                        ? 'WDF'
                        : 'SS';

                $lastOrder =
                    LaundryOrder::query()
                        ->where(
                            'service_number',
                            'like',
                            $servicePrefix . '-%'
                        )
                        ->latest('id')
                        ->first();

                $nextNumber = $lastOrder
                    ? (
                        (int) substr(
                            $lastOrder->service_number,
                            4
                        ) + 1
                    )
                    : 1;

                $serviceNumber =
                    $servicePrefix . '-' . str_pad(
                        $nextNumber,
                        5,
                        '0',
                        STR_PAD_LEFT
                    );


                /*
                * Create order.
                */
                $order = LaundryOrder::create([
                'customer_id' =>
                    $customer->id,

                'service_id' =>
                    $service->id,

                'order_source' =>
                    'customer',

                'service_number' =>
                    $serviceNumber,

                'kilos' =>
                    null,

                'load_count' =>
                    0,

                'detergent_quantity' =>
                    $detergentQuantity,

                'fabric_conditioner_quantity' =>
                    $conditionerQuantity,

                'total_amount' =>
                    $initialTotal,

                'status' =>
                    'Received',

                'payment_status' =>
                    'Unpaid',

                'paid_at' =>
                    null,

                'received_at' =>
                    now(),

                'completed_at' =>
                    null,
            ]);


            return $order;
            }
            );


            /*
            * Explicitly load everything required
            * by the confirmation page.
            */
            $order->load([
                'customer',
                'service',
            ]);


            /*
            * REDIRECT TO THE RECEIPT PAGE
            */
            return redirect()->route(
                'customer.service-confirmation',
                ['order' => $order->id]
            );
    }

    public function showServiceConfirmation(LaundryOrder $order)
    {
        $order->load([
            'customer',
            'service',
        ]);

        return view(
            'customer.service-confirmation',
            [
                'customer' => $order->customer,
                'order' => $order,
            ]
        );
    }
}