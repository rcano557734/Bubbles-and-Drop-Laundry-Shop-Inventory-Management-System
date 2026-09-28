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

        // Find existing customer
        $customerQuery = Customer::where(
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

        // Create customer if not found
        if (!$customer) {
            $lastCustomer = Customer::orderByDesc('id')->first();

            $nextCode = $lastCustomer
                ? ((int) $lastCustomer->customer_code + 1)
                : 101;

            $customer = Customer::create([
                'customer_code' => (string) $nextCode,
                'full_name' => $validated['full_name'],
                'contact_number' =>
                    $validated['contact_number'] ?? null,
            ]);
        }

        $existingOrder = $customer->laundryOrders()
            ->where('status', '!=', 'Claimed')
            ->latest()
            ->first();

        if ($existingOrder) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    "This customer already has an active laundry order ({$existingOrder->service_number}). Please wait until it is claimed before creating another order."
                );
        }

        $service = Service::findOrFail(
            $validated['service_id']
        );

        $isSelfService =
            $service->service_name === 'Self Service';

        /*
         * Self Service:
         * 1 = customer wants the shop's product
         * 0 = customer brings their own product
         *
         * WDF always gets 0 because these options
         * are only for Self Service.
         */
        $detergentQuantity = $isSelfService
            ? (int) ($validated['detergent_quantity'] ?? 0)
            : 0;

        $conditionerQuantity = $isSelfService
            ? (int) (
                $validated['fabric_conditioner_quantity']
                ?? 0
            )
            : 0;

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

        /*
         * Self Service:
         * ₱100 flat fee
         * + optional detergent
         * + optional fabric conditioner
         *
         * WDF:
         * Price is calculated later after staff enters weight.
         */
        $initialTotal = $isSelfService
            ? 100
                + ($detergentQuantity * $detergentPrice)
                + ($conditionerQuantity * $conditionerPrice)
            : 0;

        $servicePrefix =
            $service->service_name === 'Wash, Dry, and Fold'
                ? 'WDF'
                : 'SS';

        $lastOrder = LaundryOrder::where(
            'service_number',
            'like',
            $servicePrefix . '-%'
        )
            ->latest('id')
            ->first();

        $nextNumber = $lastOrder
            ? ((int) substr(
                $lastOrder->service_number,
                4
            ) + 1)
            : 1;

        $serviceNumber = $servicePrefix . '-' . str_pad(
            $nextNumber,
            5,
            '0',
            STR_PAD_LEFT
        );

        $order = LaundryOrder::create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'order_source' => 'customer',
            'service_number' => $serviceNumber,

            'kilos' => null,
            'load_count' => 0,

            'detergent_quantity' =>
                $detergentQuantity,

            'fabric_conditioner_quantity' =>
                $conditionerQuantity,

            'total_amount' =>
                $initialTotal,

            'status' => 'Received',

            'payment_status' => 'Unpaid',
            'paid_at' => null,

            'received_at' => now(),
            'completed_at' => null,
        ]);

        return view(
            'customer.service-confirmation',
            compact('customer', 'order')
        );
    }
}