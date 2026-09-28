@extends('staff.layout')

@section('title', 'Manage Order')

@section('content')

@php

    $currentStatus = $order->status;

    $isReceived = $currentStatus === 'Received';
    $isWashing = $currentStatus === 'Washing';
    $isDrying = $currentStatus === 'Drying';
    $isFolding = $currentStatus === 'Folding';
    $isReady = $currentStatus === 'Ready for Pickup';
    $isClaimed = $currentStatus === 'Claimed';

    $assignedMachine = $order->machine;

    $customerName = $order->customer->full_name;
    $serviceNumber = $order->service_number;

    $isSelfService =
        $order->service->service_name === 'Self Service';

@endphp


<div class="mb-7">

    <p class="text-sm font-semibold text-blue-600">
        Laundry Operations
    </p>

    <h1 class="mt-1 text-3xl font-extrabold text-[#0d2a7a]">
        Manage Order
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Manage laundry details, payment, and processing status.
    </p>

</div>


{{-- SUCCESS MESSAGE --}}
@if (session('success'))

    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3">

        <p class="text-sm font-semibold text-green-700">
            {{ session('success') }}
        </p>

    </div>

@endif


{{-- ERROR MESSAGE --}}
@if (session('error'))

    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">

        <p class="text-sm font-semibold text-red-700">
            {{ session('error') }}
        </p>

    </div>

@endif


{{-- VALIDATION ERRORS --}}
@if ($errors->any())

    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-4">

        <p class="text-sm font-bold text-red-700">
            The order could not be updated.
        </p>

        <ul class="mt-2 list-disc pl-5 text-sm text-red-600">

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


{{-- ORDER INFORMATION --}}
<div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">


    {{-- Service Number --}}
    <div class="rounded-2xl border border-blue-100 bg-white p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
            Service Number
        </p>

        <p class="mt-2 text-lg font-extrabold text-[#0d2a7a]">
            {{ $order->service_number }}
        </p>

    </div>


    {{-- Customer --}}
    <div class="rounded-2xl border border-blue-100 bg-white p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
            Customer
        </p>

        <p class="mt-2 text-lg font-extrabold text-slate-800">
            {{ $order->customer->full_name }}
        </p>

        @if ($order->customer->contact_number)

            <p class="mt-1 text-xs text-slate-400">
                {{ $order->customer->contact_number }}
            </p>

        @endif

    </div>


    {{-- Service --}}
    <div class="rounded-2xl border border-blue-100 bg-white p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
            Service
        </p>

        <p class="mt-2 text-lg font-extrabold text-slate-800">
            {{ $order->service->service_name }}
        </p>

        @if ($isSelfService)

            <p class="mt-1 text-xs text-slate-400">
                ₱100.00 flat service fee
            </p>

        @else

            <p class="mt-1 text-xs text-slate-400">
                ₱{{ number_format(
                    (float) $order->service->price,
                    2
                ) }}
                per load
            </p>

        @endif

    </div>

</div>


{{-- STATUS / MACHINE --}}
<section class="mb-5 rounded-2xl border border-blue-100 bg-white p-6">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Current Laundry Status
            </p>

            <p class="mt-2 text-2xl font-extrabold text-[#0d2a7a]">
                {{ $currentStatus }}
            </p>

        </div>


        {{-- MACHINE --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-4">

            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                Machine Assignment
            </p>


            @if ($assignedMachine)

                @if ($isWashing)

                    <p class="mt-1 text-sm font-extrabold text-[#0d2a7a]">
                        {{ $assignedMachine->machine_name }}
                        — TOP / Washing
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        This laundry is occupying the top washing section.
                    </p>

                @elseif ($isDrying)

                    <p class="mt-1 text-sm font-extrabold text-[#0d2a7a]">
                        {{ $assignedMachine->machine_name }}
                        — BOTTOM / Drying
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        The bottom drying section is occupied.
                        The top section is available for another order.
                    </p>

                @else

                    <p class="mt-1 text-sm font-extrabold text-emerald-700">
                        {{ $assignedMachine->machine_name }}
                        — Released
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        This laundry has already left the machine.
                    </p>

                @endif

            @else

                <p class="mt-1 text-sm font-extrabold text-slate-700">
                    Not Assigned Yet
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    A machine will be assigned automatically when washing begins.
                </p>

            @endif

        </div>

    </div>

</section>


{{-- FORM --}}
<section class="rounded-2xl border border-blue-100 bg-white p-6">

    <form
        method="POST"
        action="{{ route('staff.orders.update', $order) }}"
        class="space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- WEIGHT --}}
        @if (!$isSelfService)

            <div>

                <div class="flex items-center justify-between mb-2">

                    <label
                        for="kilos"
                        class="block text-xs font-bold uppercase tracking-wide text-blue-600"
                    >
                        Actual Weight (kg)
                    </label>

                    @if (!$isReceived)

                        <span class="text-xs font-semibold text-slate-400">
                            🔒 Locked
                        </span>

                    @endif

                </div>


                <input
                    id="kilos"
                    name="kilos"
                    type="number"
                    step="0.01"
                    min="0.01"
                    value="{{ old('kilos', $order->kilos) }}"
                    {{ $isReceived ? 'required' : 'readonly' }}
                    placeholder="Enter actual weighed kilos"
                    class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm text-slate-800 outline-none
                    {{ $isReceived
                        ? 'bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200'
                        : 'bg-slate-100 text-slate-500 cursor-not-allowed' }}"
                >


                <p class="mt-2 text-xs text-slate-400">

                    @if ($isReceived)

                        Staff enters the actual weight after the laundry is weighed.

                    @else

                        Weight is locked because processing has already started.

                    @endif

                </p>


                @error('kilos')

                    <p class="mt-2 text-sm font-semibold text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>

        @else

            {{-- SELF SERVICE WEIGHT NOTICE --}}
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">

                <p class="text-xs font-bold uppercase tracking-wide text-blue-600">
                    Laundry Weight
                </p>

                <p class="mt-2 text-xl font-extrabold text-[#0d2a7a]">
                    Not Required
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Self Service uses a flat ₱100 service fee regardless
                    of laundry weight or load count.
                </p>

            </div>

        @endif


        {{-- CALCULATION --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">


            {{-- LOAD COUNT --}}
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">

                <p class="text-xs font-bold uppercase tracking-wide text-blue-600">
                    Load Count
                </p>

                @if ($isSelfService)

                    <p class="mt-2 text-3xl font-extrabold text-[#0d2a7a]">
                        Flat Fee
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Self Service is not load-based.
                    </p>

                @else

                    <p
                        id="loadPreview"
                        class="mt-2 text-3xl font-extrabold text-[#0d2a7a]"
                    >
                        {{ $order->load_count ?: 0 }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Based on 8.50 kg per load.
                    </p>

                @endif

            </div>


            {{-- TOTAL --}}
            <div class="rounded-2xl border border-green-100 bg-green-50 p-5">

                <p class="text-xs font-bold uppercase tracking-wide text-green-600">
                    Total Price
                </p>

                <p
                    id="pricePreview"
                    class="mt-2 text-3xl font-extrabold text-green-700"
                >
                    ₱{{ number_format(
                        (float) $order->total_amount,
                        2
                    ) }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Automatically calculated.
                </p>

            </div>


            {{-- CAPACITY --}}
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">

                <p class="text-xs font-bold uppercase tracking-wide text-amber-600">
                    Capacity Rule
                </p>

                <p class="mt-2 text-lg font-extrabold text-amber-700">
                    8.50 kg = 1 load
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Applies to Wash, Dry, and Fold.
                </p>

            </div>

        </div>


        {{-- PAYMENT --}}
        <div>

            <label
                for="payment_status"
                class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600"
            >
                Payment Status
            </label>


            <select
                id="payment_status"
                name="payment_status"
                class="w-full rounded-xl border border-blue-100
                       bg-white px-4 py-3 text-sm text-slate-800
                       outline-none
                       focus:border-blue-500
                       focus:ring-2 focus:ring-blue-200"
            >

                <option
                    value="Unpaid"
                    @selected(
                        old(
                            'payment_status',
                            $order->payment_status
                        ) === 'Unpaid'
                    )
                >
                    Unpaid
                </option>

                <option
                    value="Paid"
                    @selected(
                        old(
                            'payment_status',
                            $order->payment_status
                        ) === 'Paid'
                    )
                >
                    Paid
                </option>

            </select>


            @error('payment_status')

                <p class="mt-2 text-sm font-semibold text-red-600">
                    {{ $message }}
                </p>

            @enderror


            <p class="mt-2 text-xs text-slate-400">
                Payment remains editable at every processing stage.
            </p>

        </div>


        {{-- SELF SERVICE ADD-ONS --}}
        @if ($isSelfService)

            <div class="rounded-2xl border border-blue-100 bg-slate-50 p-5">

                <div>

                    <p class="text-sm font-bold text-[#1d293d]">
                        Self Service Add-ons
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        These choices were selected by the customer.
                    </p>

                </div>


                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">


                    {{-- Detergent --}}
                    <div
                        class="rounded-xl border border-slate-200 bg-white p-4"
                    >

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Detergent Requested
                        </p>

                        <p class="mt-2 text-xl font-extrabold text-[#0d2a7a]">

                            {{ $order->detergent_quantity > 0
                                ? 'Yes'
                                : 'No' }}

                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            ₱{{ number_format(
                                $detergentPrice,
                                2
                            ) }} each.
                        </p>


                        <input
                            type="hidden"
                            name="detergent_quantity"
                            value="{{ $order->detergent_quantity ?? 0 }}"
                        >

                    </div>


                    {{-- Conditioner --}}
                    <div
                        class="rounded-xl border border-slate-200 bg-white p-4"
                    >

                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            Fabric Conditioner Requested
                        </p>

                        <p class="mt-2 text-xl font-extrabold text-[#0d2a7a]">

                            {{ $order->fabric_conditioner_quantity > 0
                                ? 'Yes'
                                : 'No' }}

                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            ₱{{ number_format(
                                $conditionerPrice,
                                2
                            ) }} each.
                        </p>


                        <input
                            type="hidden"
                            name="fabric_conditioner_quantity"
                            value="{{ $order->fabric_conditioner_quantity ?? 0 }}"
                        >

                    </div>

                </div>

            </div>

        @endif


        {{-- WORKFLOW --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                Processing Workflow
            </p>


            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs font-semibold">

                <span
                    class="rounded-full px-3 py-1.5
                    {{ $isReceived
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-slate-100 text-slate-400' }}"
                >
                    Received
                </span>

                <span class="text-slate-400">→</span>

                <span
                    class="rounded-full px-3 py-1.5
                    {{ $isWashing
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-slate-100 text-slate-400' }}"
                >
                    Washing
                </span>

                <span class="text-slate-400">→</span>

                <span
                    class="rounded-full px-3 py-1.5
                    {{ $isDrying
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-slate-100 text-slate-400' }}"
                >
                    Drying
                </span>

                <span class="text-slate-400">→</span>

                <span
                    class="rounded-full px-3 py-1.5
                    {{ $isFolding
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-slate-100 text-slate-400' }}"
                >
                    Folding
                </span>

                <span class="text-slate-400">→</span>

                <span
                    class="rounded-full px-3 py-1.5
                    {{ $isReady
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-slate-100 text-slate-400' }}"
                >
                    Ready for Pickup
                </span>

                <span class="text-slate-400">→</span>

                <span
                    class="rounded-full px-3 py-1.5
                    {{ $isClaimed
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-slate-100 text-slate-400' }}"
                >
                    Claimed
                </span>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:flex-wrap">


            {{-- RECEIVED --}}
            @if ($isReceived)

                <button
                    type="submit"
                    name="status"
                    value="Received"
                    class="rounded-xl border border-blue-200
                           bg-white px-6 py-3 text-sm font-bold
                           text-[#1e3a8a] transition hover:bg-blue-50"
                >
                    Save Details
                </button>


                <button
                    type="submit"
                    name="status"
                    value="Washing"
                    class="rounded-xl bg-[#4f74d9]
                           px-6 py-3 text-sm font-bold
                           text-white transition hover:bg-[#3f63c8]"
                    onclick="return confirm(
                        'Start washing for {{ addslashes($customerName) }}?\n\n' +
                        'Service No.: {{ addslashes($serviceNumber) }}\n\n' +
                        'A washing section will be assigned automatically.'
                    );"
                >
                    Start Washing
                </button>

            @endif


            {{-- WASHING --}}
            @if ($isWashing)

                <button
                    type="submit"
                    name="status"
                    value="Washing"
                    class="rounded-xl border border-blue-200
                           bg-white px-6 py-3 text-sm font-bold
                           text-[#1e3a8a] transition hover:bg-blue-50"
                >
                    Save Payment
                </button>


                <button
                    type="submit"
                    name="status"
                    value="Drying"
                    class="rounded-xl bg-[#4f74d9]
                           px-6 py-3 text-sm font-bold
                           text-white transition hover:bg-[#3f63c8]"
                    onclick="return confirm(
                        'Proceed to Drying for {{ addslashes($customerName) }}?\n\n' +
                        'Service No.: {{ addslashes($serviceNumber) }}\n\n' +
                        'The laundry will move to the BOTTOM of {{ addslashes($assignedMachine?->machine_name ?? 'the assigned machine') }}.'
                    );"
                >
                    Proceed to Drying
                </button>

            @endif


            {{-- DRYING --}}
            @if ($isDrying)

                <button
                    type="submit"
                    name="status"
                    value="Drying"
                    class="rounded-xl border border-blue-200
                           bg-white px-6 py-3 text-sm font-bold
                           text-[#1e3a8a] transition hover:bg-blue-50"
                >
                    Save Payment
                </button>


                <button
                    type="submit"
                    name="status"
                    value="Folding"
                    class="rounded-xl bg-[#4f74d9]
                           px-6 py-3 text-sm font-bold
                           text-white transition hover:bg-[#3f63c8]"
                    onclick="return confirm(
                        'Proceed to Folding for {{ addslashes($customerName) }}?\n\n' +
                        'Service No.: {{ addslashes($serviceNumber) }}\n\n' +
                        'The machine will be released.'
                    );"
                >
                    Proceed to Folding
                </button>

            @endif


            {{-- FOLDING --}}
            @if ($isFolding)

                <button
                    type="submit"
                    name="status"
                    value="Folding"
                    class="rounded-xl border border-blue-200
                           bg-white px-6 py-3 text-sm font-bold
                           text-[#1e3a8a] transition hover:bg-blue-50"
                >
                    Save Payment
                </button>


                <button
                    type="submit"
                    name="status"
                    value="Ready for Pickup"
                    class="rounded-xl bg-[#4f74d9]
                           px-6 py-3 text-sm font-bold
                           text-white transition hover:bg-[#3f63c8]"
                    onclick="return confirm(
                        'Mark this laundry as Ready for Pickup?\n\n' +
                        'Customer: {{ addslashes($customerName) }}\n' +
                        'Service No.: {{ addslashes($serviceNumber) }}'
                    );"
                >
                    Proceed to Ready for Pickup
                </button>

            @endif


            {{-- READY --}}
            @if ($isReady)

                <button
                    type="submit"
                    name="status"
                    value="Ready for Pickup"
                    class="rounded-xl border border-blue-200
                           bg-white px-6 py-3 text-sm font-bold
                           text-[#1e3a8a] transition hover:bg-blue-50"
                >
                    Save Payment
                </button>


                @if ($order->payment_status === 'Paid')

                    <button
                        type="submit"
                        name="status"
                        value="Claimed"
                        class="rounded-xl bg-emerald-600
                               px-6 py-3 text-sm font-bold
                               text-white transition hover:bg-emerald-700"
                        onclick="return confirm(
                            'Confirm Laundry Claim\n\n' +
                            'Customer: {{ addslashes($customerName) }}\n' +
                            'Service No.: {{ addslashes($serviceNumber) }}\n\n' +
                            'Is this the correct customer?'
                        );"
                    >
                        Confirm Claim
                    </button>

                @else

                    <button
                        type="button"
                        disabled
                        class="rounded-xl bg-slate-300
                               px-6 py-3 text-sm font-bold
                               text-slate-500 cursor-not-allowed"
                    >
                        Confirm Claim
                    </button>


                    <p class="w-full text-xs font-semibold text-red-600">
                        The laundry must be marked as Paid before it can be Claimed.
                    </p>

                @endif

            @endif


            <a
                href="{{ route('staff.orders.index') }}"
                class="rounded-xl border border-blue-100 bg-white
                       px-6 py-3 text-center text-sm font-bold
                       text-slate-600 transition hover:bg-blue-50"
            >
                Back to Orders
            </a>

        </div>

    </form>

</section>


{{-- WDF CALCULATION --}}
@if (!$isSelfService)

<script>

    const kilosInput =
        document.getElementById('kilos');

    const loadPreview =
        document.getElementById('loadPreview');

    const pricePreview =
        document.getElementById('pricePreview');

    const pricePerLoad =
        {{ (float) $order->service->price }};


    function updateCalculation()
    {
        if (!kilosInput) {
            return;
        }

        const kilos =
            parseFloat(kilosInput.value);


        if (!kilos || kilos <= 0) {

            loadPreview.textContent =
                '0';

            pricePreview.textContent =
                '₱0.00';

            return;
        }


        const loads =
            Math.ceil(
                kilos / 8.50
            );


        const total =
            loads * pricePerLoad;


        loadPreview.textContent =
            loads;

        pricePreview.textContent =
            '₱' + total.toFixed(2);
    }


    kilosInput.addEventListener(
        'input',
        updateCalculation
    );

    updateCalculation();

</script>

@endif

@endsection