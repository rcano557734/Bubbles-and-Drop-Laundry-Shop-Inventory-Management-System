<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Avail Service — Bubble & Drop</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-[#d6ecfd]">

    <!-- Page -->
    <div class="flex-1 flex flex-col relative overflow-hidden">

        <!-- Top-right Branding / Wave -->
        <div class="absolute top-0 right-0 w-[min(600px,100vw)] h-[200px] pointer-events-none">

            <svg
                class="absolute inset-0 w-full h-full"
                viewBox="0 0 600 200"
                preserveAspectRatio="none"
                aria-hidden="true">

                <path
                    d="M20 0
                       C95 10 145 85 250 118
                       C370 150 500 140 600 170
                       L600 0Z"
                    fill="#7fa8d1"
                    opacity=".5"
                    transform="translate(0 5)" />

                <path
                    d="M20 0
                       C95 10 145 85 250 118
                       C370 150 500 140 600 170
                       L600 0Z"
                    fill="#98d0ff" />

                <path
                    d="M45 0
                       C115 10 165 68 262 92
                       C380 120 500 104 600 112
                       L600 0Z"
                    fill="#1d3fa6" />

            </svg>

            <div class="absolute top-5 right-6 flex items-center gap-3 text-white">

                <div class="w-10 h-10 rounded-lg bg-white/15 flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-5 h-5">

                        <path d="M12 3c3 4 6 6.5 6 10a6 6 0 0 1-12 0c0-3.5 3-6 6-10z" />

                    </svg>

                </div>

                <div>
                    <p class="font-bold text-sm leading-tight">
                        Bubbles &amp; Drop
                    </p>

                    <p class="text-xs text-blue-100">
                        Laundry Shop
                    </p>
                </div>

            </div>
        </div>


        <!-- Main -->
        <main class="flex-1 flex items-start justify-center px-6 pt-28 pb-8">

            <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-[290px_1fr] gap-8">

                <!-- Service Preview -->
                <section
                    class="rounded-[22px] border border-blue-200 bg-[#d9e6fd]
                           min-h-[340px] flex flex-col items-center justify-center
                           p-6">

                    <div class="w-[130px] h-[130px] rounded-2xl
                                bg-[#96c2fc]
                                flex items-center justify-center
                                text-[#1420ff]">

                        <svg
                            id="serviceIconWdf"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 96 96"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-24 h-24">

                            <rect x="30" y="10" width="58" height="72" rx="3" />
                            <line x1="30" y1="24" x2="88" y2="24" />
                            <circle cx="38" cy="17" r="2.5" />
                            <line x1="58" y1="16" x2="74" y2="16" />
                            <circle cx="62" cy="52" r="19" />
                            <circle cx="62" cy="52" r="12" />
                            <rect x="8" y="40" width="34" height="8" rx="3" />
                            <rect x="8" y="50" width="34" height="8" rx="3" />
                            <path d="M6 62h18l12-4 6 2-16 12H6z" />

                        </svg>

                    </div>

                    <h2
                        id="serviceTitle"
                        class="mt-5 text-2xl font-extrabold text-[#b3141c] text-center">

                        Wash, Dry, and Fold

                    </h2>

                    <p
                        id="serviceDescription"
                        class="mt-2 text-sm text-slate-600 text-center max-w-xs">

                        ₱210 per load. Final price is determined after the laundry is weighed.

                    </p>

                </section>


                <!-- Form -->
                <section class="w-full">

                    <div class="mb-6">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-[#0d2a7a]">
                            Avail Service
                        </h1>

                        <p class="mt-1 text-base text-[#3560e0]">
                            Select a service and provide your customer information.
                        </p>
                    </div>


                    @if ($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                            <p class="text-sm font-semibold text-red-700">
                                Please check the information you entered.
                            </p>
                        </div>
                    @endif


                    <form
                        method="POST"
                        action="{{ route('customer.create-order') }}"
                        class="space-y-6">

                        @csrf


                        <!-- Service Selection -->
                        <div>

                            <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#3560e0]">
                                Select Service
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                @foreach ($services as $service)

                                    @php
                                        $isWdf = $service->service_name === 'Wash, Dry, and Fold';
                                        $isSelected = old('service_id', $services->first()->id ?? null) == $service->id;
                                    @endphp

                                    <label class="cursor-pointer">

                                        <input
                                            type="radio"
                                            name="service_id"
                                            value="{{ $service->id }}"
                                            class="peer sr-only service-option"
                                            data-service="{{ $service->service_name }}"
                                            data-price="{{ $service->price }}"
                                            data-wdf="{{ $isWdf ? '1' : '0' }}"
                                            {{ $isSelected ? 'checked' : '' }}
                                            required>

                                        <div
                                            class="rounded-2xl border-2 p-5
                                                   transition-all
                                                   peer-checked:border-blue-600
                                                   peer-checked:bg-blue-50
                                                   peer-checked:shadow-md
                                                   hover:border-blue-300">

                                            <div class="flex items-start gap-4">

                                                <div
                                                    class="w-16 h-16 shrink-0 rounded-xl
                                                           flex items-center justify-center
                                                           {{ $isWdf
                                                                ? 'bg-red-100 text-red-600'
                                                                : 'bg-blue-100 text-blue-600' }}">

                                                    @if ($isWdf)

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="w-9 h-9">

                                                            <path d="M3 6h18" />
                                                            <path d="M5 6v14h14V6" />
                                                            <path d="M8 3h8l1 3H7l1-3Z" />
                                                            <circle cx="12" cy="13" r="3" />

                                                        </svg>

                                                    @else

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="w-9 h-9">

                                                            <rect x="4" y="3" width="16" height="18" rx="2" />
                                                            <circle cx="12" cy="13" r="4" />
                                                            <path d="M8 7h8" />

                                                        </svg>

                                                    @endif

                                                </div>

                                                <div class="flex-1">

                                                    <h3 class="font-bold text-slate-800">
                                                        {{ $service->service_name }}
                                                    </h3>

                                                    <p class="mt-1 text-sm text-slate-500">

                                                        @if ($isWdf)
                                                            ₱{{ number_format($service->price, 2) }} per load
                                                        @else
                                                            ₱{{ number_format($service->price, 2) }}
                                                        @endif

                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </label>

                                @endforeach

                            </div>

                            @error('service_id')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <!-- Customer Information -->
                        <div class="rounded-2xl bg-white border border-blue-100 p-6">

                            <h2 class="text-lg font-bold text-[#0d2a7a]">
                                Customer Information
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Enter the customer's information for this service request.
                            </p>


                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                                <!-- Full Name -->
                                <div>

                                    <label
                                        for="full_name"
                                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#3560e0]">

                                        Full Name

                                    </label>

                                    <input
                                        id="full_name"
                                        type="text"
                                        name="full_name"
                                        value="{{ old('full_name') }}"
                                        placeholder="Enter full name"
                                        required
                                        class="w-full rounded-xl border border-blue-100
                                               bg-white px-4 py-3 text-sm text-slate-800
                                               placeholder:text-slate-400
                                               outline-none
                                               focus:border-blue-500
                                               focus:ring-2 focus:ring-blue-200">

                                    @error('full_name')
                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                <!-- Contact -->
                                <div>

                                    <label
                                        for="contact_number"
                                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#3560e0]">

                                        Contact Number
                                        <span class="font-normal normal-case text-slate-400">
                                            (Optional)
                                        </span>

                                    </label>

                                    <input
                                        id="contact_number"
                                        type="tel"
                                        name="contact_number"
                                        value="{{ old('contact_number') }}"
                                        placeholder="09XXXXXXXXX"
                                        maxlength="11"
                                        inputmode="numeric"
                                        oninput="this.value=this.value.replace(/\D/g,'')"
                                        class="w-full rounded-xl border border-blue-100
                                               bg-white px-4 py-3 text-sm text-slate-800
                                               placeholder:text-slate-400
                                               outline-none
                                               focus:border-blue-500
                                               focus:ring-2 focus:ring-blue-200">

                                    @error('contact_number')
                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        <!-- Weight Notice -->
                        <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">

                            <div class="flex gap-3">

                                <div class="shrink-0 text-xl">
                                    ⚖️
                                </div>

                                <div>

                                    <p class="font-semibold text-blue-900">
                                        Weight will be entered by staff
                                    </p>

                                    <p class="mt-1 text-sm text-blue-700">
                                        You do not need to enter the laundry weight.
                                        Staff will weigh the laundry after receiving it,
                                        then the system will calculate the required loads
                                        and final price.

                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- Actions -->
                        <div class="flex flex-col-reverse sm:flex-row justify-between gap-3 pt-2">

                            <a
                                href="{{ route('customer.landing') }}"
                                class="h-11 px-6 rounded-xl border border-blue-200
                                       bg-white text-blue-700
                                       font-semibold text-sm
                                       flex items-center justify-center
                                       hover:bg-blue-50 transition">

                                Back

                            </a>

                            <button
                                type="submit"
                                class="h-11 px-7 rounded-xl
                                       bg-[#4f74d9] text-white
                                       font-semibold text-sm
                                       shadow-sm
                                       hover:bg-[#3f63c8]
                                       transition">

                                Confirm Service

                            </button>

                        </div>

                    </form>

                </section>

            </div>

        </main>


        <!-- Footer -->
        <footer class="bg-[#1d3fa6] text-white px-6 py-4">

            <div class="max-w-7xl mx-auto flex justify-end">

                <div class="flex items-center gap-3 text-right">

                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-5 h-5">

                            <path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z" />
                            <circle cx="12" cy="9.5" r="2.5" />

                        </svg>

                    </div>

                    <div>

                        <p class="font-semibold text-sm">
                            Matina Aplaya Road
                        </p>

                        <p class="text-xs text-blue-200">
                            Davao City, Davao Region
                        </p>

                    </div>

                </div>

            </div>

        </footer>

    </div>


    <!-- Small service-preview update -->
    <script>
        const serviceOptions = document.querySelectorAll('.service-option');

        const serviceTitle = document.getElementById('serviceTitle');
        const serviceDescription = document.getElementById('serviceDescription');

        function updateServicePreview() {
            const selected = document.querySelector('.service-option:checked');

            if (!selected) {
                return;
            }

            const serviceName = selected.dataset.service;
            const price = Number(selected.dataset.price);
            const isWdf = selected.dataset.wdf === '1';

            serviceTitle.textContent = serviceName;

            if (isWdf) {
                serviceTitle.className =
                    'mt-5 text-2xl font-extrabold text-[#b3141c] text-center';

                serviceDescription.textContent =
                    `₱${price.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })} per load. Final price is determined after the laundry is weighed.`;
            } else {
                serviceTitle.className =
                    'mt-5 text-2xl font-extrabold text-[#1d3a8f] text-center';

                serviceDescription.textContent =
                    `₱${price.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })} service fee.`;
            }
        }

        serviceOptions.forEach(option => {
            option.addEventListener('change', updateServicePreview);
        });

        updateServicePreview();
    </script>

</body>

</html>