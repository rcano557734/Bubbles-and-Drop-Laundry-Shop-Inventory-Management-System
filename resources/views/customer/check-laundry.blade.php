<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Check My Laundry — Bubble & Drop</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-[#d6ecfd]">

    <div class="flex-1 flex flex-col relative overflow-hidden">

        <!-- Top-right Branding -->
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
        <main class="flex-1 flex items-center justify-center px-6 py-12">

            <div class="w-full max-w-xl">

                <!-- Header -->
                <div class="text-center mb-7">

                    <div class="mx-auto w-20 h-20 rounded-2xl
                                bg-[#96c2fc]
                                text-[#1420ff]
                                flex items-center justify-center">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 96 96"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="3"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-14 h-14">

                            <circle cx="42" cy="42" r="23" />
                            <line x1="59" y1="59" x2="79" y2="79" />
                            <path d="M34 42h16" />
                            <path d="M42 34v16" />

                        </svg>

                    </div>

                    <h1 class="mt-5 text-3xl md:text-4xl font-extrabold text-[#0d2a7a]">
                        Check My Laundry
                    </h1>

                    <p class="mt-2 text-sm md:text-base text-[#3560e0]">
                        Enter your information to check your current laundry status.
                    </p>

                </div>


                <!-- Form Card -->
                <section class="bg-white rounded-[24px] border border-blue-100
                                shadow-[0_14px_40px_rgba(29,63,166,0.14)]
                                p-6 md:p-8">

                    @if ($errors->any())

                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">

                            <p class="text-sm font-semibold text-red-700">
                                We couldn't find your laundry record.
                            </p>

                            <div class="mt-1 text-sm text-red-600">
                                {{ $errors->first() }}
                            </div>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('customer.search-laundry') }}"
                        class="space-y-5">

                        @csrf


                        <!-- Customer ID -->
                        <div>

                            <label
                                for="customer_code"
                                class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#3560e0]">

                                Customer ID

                            </label>

                            <input
                                id="customer_code"
                                type="text"
                                name="customer_code"
                                value="{{ old('customer_code') }}"
                                placeholder="Enter your Customer ID"
                                required
                                autofocus
                                class="w-full rounded-xl border border-blue-100
                                       bg-white px-4 py-3.5 text-sm text-slate-800
                                       placeholder:text-slate-400
                                       outline-none
                                       focus:border-blue-500
                                       focus:ring-2 focus:ring-blue-200">

                            @error('customer_code')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


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
                                placeholder="Enter your full name"
                                required
                                class="w-full rounded-xl border border-blue-100
                                       bg-white px-4 py-3.5 text-sm text-slate-800
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


                        <!-- Contact Number -->
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
                                       bg-white px-4 py-3.5 text-sm text-slate-800
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


                        <!-- Information -->
                        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-4">

                            <div class="flex gap-3">

                                <div class="shrink-0 text-lg">
                                    🔎
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-blue-900">
                                        Why do I need my Customer ID?
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-blue-700">
                                        Your Customer ID helps us find the correct customer record,
                                        especially when multiple customers have the same name.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- Actions -->
                        <div class="flex flex-col-reverse sm:flex-row justify-between gap-3 pt-2">

                            <a
                                href="{{ route('customer.landing') }}"
                                class="h-11 px-6 rounded-xl
                                       border border-blue-200
                                       bg-white text-blue-700
                                       font-semibold text-sm
                                       flex items-center justify-center
                                       hover:bg-blue-50
                                       transition">

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

                                Check Laundry

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

</body>

</html>