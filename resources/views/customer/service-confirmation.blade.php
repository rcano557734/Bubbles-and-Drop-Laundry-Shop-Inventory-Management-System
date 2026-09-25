<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Service Confirmation — Bubble & Drop</title>

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

            <div class="w-full max-w-lg">

                <!-- Confirmation Header -->
                <div class="text-center mb-6">

                    <div class="mx-auto w-16 h-16 rounded-full
                                bg-green-100 text-green-600
                                flex items-center justify-center">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-8 h-8">

                            <path d="M20 6L9 17l-5-5" />

                        </svg>

                    </div>

                    <h1 class="mt-4 text-3xl font-extrabold text-[#0d2a7a]">
                        Service Confirmed
                    </h1>

                    <p class="mt-1 text-sm text-blue-600">
                        Your laundry service request has been recorded.
                    </p>

                </div>


                <!-- Receipt -->
                <section class="bg-white rounded-[26px] border border-blue-100
                                shadow-[0_14px_40px_rgba(29,63,166,0.18)]
                                overflow-hidden">

                    <!-- Service Number -->
                    <div class="text-center bg-[#1d3fa6] text-white px-6 py-7">

                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-200">
                            Service Number
                        </p>

                        <p class="mt-2 text-5xl font-extrabold tracking-tight">
                            {{ $order->service_number }}
                        </p>

                    </div>


                    <!-- Information -->
                    <div class="px-6 py-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!-- Customer -->
                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                                    Customer
                                </p>

                                <p class="mt-1 font-bold text-slate-800">
                                    {{ $order->customer->full_name }}
                                </p>

                            </div>


                            <!-- Customer ID -->
                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                                    Customer ID
                                </p>

                                <p class="mt-1 font-bold text-slate-800">
                                    {{ $order->customer->customer_code }}
                                </p>

                            </div>


                            <!-- Service -->
                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                                    Service
                                </p>

                                <p class="mt-1 font-bold text-slate-800">
                                    {{ $order->service->service_name }}
                                </p>

                            </div>


                            <!-- Status -->
                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                                    Status
                                </p>

                                <span class="inline-flex mt-1 px-3 py-1 rounded-full
                                             bg-amber-100 text-amber-700
                                             text-sm font-bold">

                                    {{ $order->status }}

                                </span>

                            </div>

                        </div>


                        <!-- Date and Time -->
                        <div class="mt-4 rounded-xl border border-blue-100 p-4">

                            <div class="flex items-center justify-between gap-4">

                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                                        Date
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-800">
                                        {{ $order->received_at?->format('F d, Y') ?? now()->format('F d, Y') }}
                                    </p>
                                </div>

                                <div class="text-right">

                                    <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                                        Time
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-slate-800">
                                        {{ $order->received_at?->format('h:i A') ?? now()->format('h:i A') }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- Weight Notice -->
                        <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-4">

                            <div class="flex gap-3">

                                <div class="text-lg shrink-0">
                                    ⚖️
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-blue-900">
                                        Laundry weight will be determined by staff.
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-blue-700">
                                        Your laundry will be weighed after it is received.
                                        The system will then calculate the number of loads
                                        and the applicable final price.

                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- Important Reminder -->
                        <div class="mt-5 text-center">

                            <p class="text-sm text-slate-500">
                                Please keep your service number for checking your laundry status.
                            </p>

                        </div>


                        <!-- Done -->
                        <div class="mt-6">

                            <a
                                href="{{ route('customer.landing') }}"
                                class="w-full h-12 rounded-xl
                                       bg-[#4f74d9] text-white
                                       font-bold text-sm
                                       flex items-center justify-center
                                       hover:bg-[#3f63c8]
                                       transition">

                                Done

                            </a>

                        </div>

                    </div>

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