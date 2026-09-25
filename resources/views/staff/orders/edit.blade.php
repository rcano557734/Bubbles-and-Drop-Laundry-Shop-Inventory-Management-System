@extends('staff.layout')

@section('title', 'Manage Order')

@section('content')

    <div class="mb-7">
        <p class="text-sm font-semibold text-blue-600">
            Laundry Operations
        </p>

        <h1 class="mt-1 text-3xl font-extrabold text-[#0d2a7a]">
            Manage Order
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Update the customer's laundry weight, calculated loads, total price, and processing status.
        </p>
    </div>

    {{-- Order Information --}}
    <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">

        <div class="rounded-2xl border border-blue-100 bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Service Number
            </p>

            <p class="mt-2 text-lg font-extrabold text-[#0d2a7a]">
                {{ $order->service_number }}
            </p>
        </div>

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

        <div class="rounded-2xl border border-blue-100 bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Service
            </p>

            <p class="mt-2 text-lg font-extrabold text-slate-800">
                {{ $order->service->service_name }}
            </p>

            <p class="mt-1 text-xs text-slate-400">
                ₱{{ number_format($order->service->price, 2) }} per load
            </p>
        </div>

    </div>

    {{-- Manage Form --}}
    <section class="rounded-2xl border border-blue-100 bg-white p-6">

        <form
            method="POST"
            action="{{ route('staff.orders.update', $order) }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            {{-- Weight --}}
            <div>
                <label
                    for="kilos"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600"
                >
                    Actual Weight (kg)
                </label>

                <input
                    id="kilos"
                    name="kilos"
                    type="number"
                    step="0.01"
                    min="0.01"
                    value="{{ old('kilos', $order->kilos) }}"
                    required
                    placeholder="Enter actual weighed kilos"
                    class="w-full rounded-xl border border-blue-100 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >

                <p class="mt-2 text-xs text-slate-400">
                    Staff enters the actual weight after the laundry is weighed.
                </p>

                @error('kilos')
                    <p class="mt-2 text-sm font-semibold text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Calculation Preview --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-blue-600">
                        Load Count
                    </p>

                    <p
                        id="loadPreview"
                        class="mt-2 text-3xl font-extrabold text-[#0d2a7a]"
                    >
                        {{ $order->load_count ?: 0 }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Based on 8.50 kg per load
                    </p>
                </div>

                <div class="rounded-2xl border border-green-100 bg-green-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-green-600">
                        Total Price
                    </p>

                    <p
                        id="pricePreview"
                        class="mt-2 text-3xl font-extrabold text-green-700"
                    >
                        ₱{{ number_format($order->total_amount, 2) }}
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Automatically calculated
                    </p>
                </div>

                <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-600">
                        Capacity Rule
                    </p>

                    <p class="mt-2 text-lg font-extrabold text-amber-700">
                        8.50 kg = 1 load
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Anything above 8.50 kg adds another load.
                    </p>
                </div>

            </div>

            {{-- Status --}}
            <div>
                <label
                    for="status"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600"
                >
                    Laundry Status
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    class="w-full rounded-xl border border-blue-100 bg-white px-4 py-3 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >
                    @foreach ([
                        'Received',
                        'Washing',
                        'Drying',
                        'Folding',
                        'Ready for Pickup',
                        'Claimed'
                    ] as $option)

                        <option
                            value="{{ $option }}"
                            @selected(old('status', $order->status) === $option)
                        >
                            {{ $option }}
                        </option>

                    @endforeach
                </select>

                @error('status')
                    <p class="mt-2 text-sm font-semibold text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col gap-3 pt-2 sm:flex-row">

                <button
                    type="submit"
                    class="rounded-xl bg-[#4f74d9] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#3f63c8]"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('staff.orders.index') }}"
                    class="rounded-xl border border-blue-100 bg-white px-6 py-3 text-center text-sm font-bold text-slate-600 transition hover:bg-blue-50"
                >
                    Cancel
                </a>

            </div>

        </form>

    </section>

    <script>
        const kilosInput = document.getElementById('kilos');
        const loadPreview = document.getElementById('loadPreview');
        const pricePreview = document.getElementById('pricePreview');

        const pricePerLoad = {{ (float) $order->service->price }};

        function updateCalculation() {
            const kilos = parseFloat(kilosInput.value);

            if (!kilos || kilos <= 0) {
                loadPreview.textContent = '0';
                pricePreview.textContent = '₱0.00';
                return;
            }

            const loads = Math.ceil(kilos / 8.50);
            const total = loads * pricePerLoad;

            loadPreview.textContent = loads;
            pricePreview.textContent = '₱' + total.toFixed(2);
        }

        kilosInput.addEventListener('input', updateCalculation);

        updateCalculation();
    </script>

@endsection