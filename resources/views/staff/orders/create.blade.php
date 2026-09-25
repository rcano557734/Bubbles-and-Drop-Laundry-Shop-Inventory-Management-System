@extends('staff.layout')

@section('title', 'Add Laundry Order')

@section('content')

    <div class="mb-7">
        <p class="text-sm font-semibold text-blue-600">
            Laundry Operations
        </p>

        <h1 class="mt-1 text-3xl font-extrabold text-[#0d2a7a]">
            Add Laundry Order
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Record a customer's laundry service on their behalf.
        </p>
    </div>

    <section class="max-w-3xl rounded-2xl border border-blue-100 bg-white p-6">

        <form method="POST"
              action="{{ route('staff.orders.store') }}"
              class="space-y-6">

            @csrf

            {{-- Customer Name --}}
            <div>
                <label
                    for="full_name"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600"
                >
                    Customer Name
                </label>

                <input
                    id="full_name"
                    type="text"
                    name="full_name"
                    value="{{ old('full_name') }}"
                    required
                    placeholder="Enter customer's full name"
                    class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >

                @error('full_name')
                    <p class="mt-2 text-sm font-semibold text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Contact Number --}}
            <div>
                <label
                    for="contact_number"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600"
                >
                    Contact Number
                    <span class="font-normal text-slate-400">(Optional)</span>
                </label>

                <input
                    id="contact_number"
                    type="text"
                    name="contact_number"
                    value="{{ old('contact_number') }}"
                    placeholder="09XXXXXXXXX"
                    class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >

                @error('contact_number')
                    <p class="mt-2 text-sm font-semibold text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Service --}}
            <div>
                <label
                    for="service_id"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600"
                >
                    Service
                </label>

                <select
                    id="service_id"
                    name="service_id"
                    required
                    class="w-full rounded-xl border border-blue-100 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >
                    <option value="">Select a service</option>

                    @foreach ($services as $service)
                        <option
                            value="{{ $service->id }}"
                            @selected(old('service_id') == $service->id)
                        >
                            {{ $service->service_name }}
                            — ₱{{ number_format($service->price, 2) }}
                        </option>
                    @endforeach
                </select>

                @error('service_id')
                    <p class="mt-2 text-sm font-semibold text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Important Notice --}}
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                <p class="text-sm font-bold text-amber-700">
                    Weight will be entered by staff after receiving the laundry.
                </p>

                <p class="mt-1 text-xs text-amber-700/80">
                    The system will then calculate the number of loads and total price
                    using the 8.50 kg per load rule.
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col gap-3 pt-2 sm:flex-row">

                <button
                    type="submit"
                    class="rounded-xl bg-[#4f74d9] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#3f63c8]"
                >
                    Create Order
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

@endsection