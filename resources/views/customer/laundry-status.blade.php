<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Laundry Status — Bubble & Drop</title>

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


        @php

            $statuses = [
                'Received' => [
                    'label' => 'Received',
                    'icon' => '📦',
                ],
                'Washing' => [
                    'label' => 'Washing',
                    'icon' => '🫧',
                ],
                'Drying' => [
                    'label' => 'Drying',
                    'icon' => '💨',
                ],
                'Folding' => [
                    'label' => 'Folding',
                    'icon' => '👕',
                ],
                'Ready for Pickup' => [
                    'label' => 'Ready',
                    'icon' => '✅',
                ],
                'Claimed' => [
                    'label' => 'Claimed',
                    'icon' => '🎉',
                ],
            ];

            $statusKeys = array_keys($statuses);

            $currentIndex = array_search($order->status, $statusKeys, true);

            if ($currentIndex === false) {
                $currentIndex = 0;
            }

        @endphp


        <!-- Main -->
        <main class="flex-1 flex items-start justify-center px-6 pt-28 pb-12">

            <div class="w-full max-w-5xl">

                <!-- Page Heading -->
                <div class="mb-7">

                    <p class="text-sm font-semibold text-blue-600">
                        Laundry Tracking
                    </p>

                    <h1 class="mt-1 text-3xl md:text-4xl font-extrabold text-[#0d2a7a]">
                        My Laundry
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Here is the current status of your laundry order.
                    </p>

                </div>


                <!-- Service Header -->
                <section class="bg-white rounded-[24px] border border-blue-100
                                shadow-[0_14px_40px_rgba(29,63,166,0.12)]
                                overflow-hidden">

                    <div class="bg-[#1d3fa6] text-white px-6 py-6">

                        <div class="flex flex-col md:flex-row
                                    md:items-center md:justify-between gap-5">

                            <div>

                                <p class="text-xs uppercase tracking-[0.2em] text-blue-200 font-bold">
                                    Service Number
                                </p>

                                <p class="mt-1 text-4xl font-extrabold">
                                    {{ $order->service_number }}
                                </p>

                            </div>


                            <div class="md:text-right">

                                <p class="text-xs uppercase tracking-wide text-blue-200 font-bold">
                                    Current Status
                                </p>

                                <span
                                    class="inline-flex mt-2 px-4 py-2 rounded-full
                                           bg-white text-blue-800
                                           text-sm font-extrabold">

                                    {{ $order->status }}

                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- Customer / Service Information -->
                    <div class="p-6 md:p-8">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <!-- Customer -->
                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs uppercase tracking-wide font-bold text-blue-500">
                                    Customer
                                </p>

                                <p class="mt-1 font-bold text-slate-800">
                                    {{ $customer->full_name }}
                                </p>

                            </div>


                            <!-- Customer ID -->
                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs uppercase tracking-wide font-bold text-blue-500">
                                    Customer ID
                                </p>

                                <p class="mt-1 font-bold text-slate-800">
                                    {{ $customer->customer_code }}
                                </p>

                            </div>


                            <!-- Service -->
                            <div class="rounded-xl bg-blue-50 p-4">

                                <p class="text-xs uppercase tracking-wide font-bold text-blue-500">
                                    Service
                                </p>

                                <p class="mt-1 font-bold text-slate-800">
                                    {{ $order->service->service_name }}
                                </p>

                            </div>

                        </div>


                        <!-- Progress -->
                        <div class="mt-8">

                            <h2 class="text-lg font-bold text-[#0d2a7a]">
                                Laundry Progress
                            </h2>

                            <div class="mt-6">

                                <div class="hidden md:grid md:grid-cols-6 relative">

                                    <!-- Connecting line -->
                                    <div
                                        class="absolute left-[8%] right-[8%] top-5 h-1 bg-blue-100 rounded-full">
                                    </div>

                                    @foreach ($statusKeys as $index => $status)

                                        @php
                                            $completed = $index <= $currentIndex;
                                            $isCurrent = $index === $currentIndex;
                                        @endphp

                                        <div class="relative flex flex-col items-center text-center">

                                            <div
                                                class="w-10 h-10 rounded-full
                                                       flex items-center justify-center
                                                       text-sm
                                                       {{ $completed
                                                            ? 'bg-[#4f74d9] text-white'
                                                            : 'bg-blue-100 text-blue-400' }}
                                                       {{ $isCurrent
                                                            ? 'ring-4 ring-blue-100'
                                                            : '' }}">

                                                {{ $statuses[$status]['icon'] }}

                                            </div>

                                            <p
                                                class="mt-3 text-xs font-bold
                                                       {{ $isCurrent
                                                            ? 'text-[#1d3fa6]'
                                                            : ($completed
                                                                ? 'text-slate-700'
                                                                : 'text-slate-400') }}">

                                                {{ $statuses[$status]['label'] }}

                                            </p>

                                        </div>

                                    @endforeach

                                </div>


                                <!-- Mobile progress -->
                                <div class="md:hidden space-y-3">

                                    @foreach ($statusKeys as $index => $status)

                                        @php
                                            $completed = $index <= $currentIndex;
                                            $isCurrent = $index === $currentIndex;
                                        @endphp

                                        <div
                                            class="flex items-center gap-3 rounded-xl p-3
                                                   {{ $isCurrent
                                                        ? 'bg-blue-50 border border-blue-200'
                                                        : 'bg-slate-50' }}">

                                            <div
                                                class="w-9 h-9 shrink-0 rounded-full
                                                       flex items-center justify-center
                                                       {{ $completed
                                                            ? 'bg-[#4f74d9] text-white'
                                                            : 'bg-blue-100 text-blue-400' }}">

                                                {{ $statuses[$status]['icon'] }}

                                            </div>

                                            <div>

                                                <p
                                                    class="text-sm font-bold
                                                           {{ $isCurrent
                                                                ? 'text-[#1d3fa6]'
                                                                : ($completed
                                                                    ? 'text-slate-700'
                                                                    : 'text-slate-400') }}">

                                                    {{ $statuses[$status]['label'] }}

                                                </p>

                                                @if ($isCurrent)

                                                    <p class="text-xs text-blue-600 mt-0.5">
                                                        Current stage
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>


                        <!-- Order Details -->
                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            <!-- Weight -->
                            <div class="rounded-xl border border-blue-100 p-4">

                                <p class="text-xs uppercase tracking-wide font-bold text-blue-500">
                                    Laundry Weight
                                </p>

                                @if ($order->kilos !== null)

                                    <p class="mt-1 text-xl font-extrabold text-slate-800">
                                        {{ number_format($order->kilos, 2) }} kg
                                    </p>

                                @else

                                    <p class="mt-1 text-sm font-bold text-slate-500">
                                        Pending weighing
                                    </p>

                                @endif

                            </div>


                            <!-- Loads -->
                            <div class="rounded-xl border border-blue-100 p-4">

                                <p class="text-xs uppercase tracking-wide font-bold text-blue-500">
                                    Loads
                                </p>

                                @if ($order->load_count > 0)

                                    <p class="mt-1 text-xl font-extrabold text-slate-800">
                                        {{ $order->load_count }}
                                    </p>

                                @else

                                    <p class="mt-1 text-sm font-bold text-slate-500">
                                        Pending
                                    </p>

                                @endif

                            </div>


                            <!-- Total -->
                            <div class="rounded-xl border border-blue-100 p-4">

                                <p class="text-xs uppercase tracking-wide font-bold text-blue-500">
                                    Total Price
                                </p>

                                @if ($order->total_amount > 0)

                                    <p class="mt-1 text-xl font-extrabold text-slate-800">
                                        ₱{{ number_format($order->total_amount, 2) }}
                                    </p>

                                @else

                                    <p class="mt-1 text-sm font-bold text-slate-500">
                                        Pending weighing
                                    </p>

                                @endif

                            </div>


                            <!-- Received -->
                            <div class="rounded-xl border border-blue-100 p-4">

                                <p class="text-xs uppercase tracking-wide font-bold text-blue-500">
                                    Received
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-800">
                                    {{ $order->received_at?->format('M d, Y') ?? '—' }}
                                </p>

                            </div>

                        </div>


                        <!-- Ready Message -->
                        @if ($order->status === 'Ready for Pickup')

                            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 p-5">

                                <div class="flex gap-3 items-start">

                                    <div class="text-2xl">
                                        ✅
                                    </div>

                                    <div>

                                        <p class="font-extrabold text-green-800">
                                            Your laundry is ready for pickup!
                                        </p>

                                        <p class="mt-1 text-sm text-green-700">
                                            Please bring or remember your service number
                                            <strong>{{ $order->service_number }}</strong>
                                            when claiming your laundry.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @elseif ($order->status === 'Claimed')

                            <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-5">

                                <div class="flex gap-3 items-start">

                                    <div class="text-2xl">
                                        🎉
                                    </div>

                                    <div>

                                        <p class="font-extrabold text-blue-800">
                                            Laundry claimed
                                        </p>

                                        <p class="mt-1 text-sm text-blue-700">
                                            This laundry order has already been claimed.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-5">

                                <div class="flex gap-3 items-start">

                                    <div class="text-xl">
                                        🫧
                                    </div>

                                    <div>

                                        <p class="font-bold text-blue-900">
                                            Your laundry is being processed.
                                        </p>

                                        <p class="mt-1 text-sm text-blue-700">
                                            Check this page again later for the latest status.
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @endif


                        <!-- Back -->
                        <div class="mt-7 flex justify-between items-center gap-3">

                            <a
                                href="{{ route('customer.check-laundry') }}"
                                class="h-11 px-6 rounded-xl
                                       border border-blue-200
                                       bg-white text-blue-700
                                       font-semibold text-sm
                                       flex items-center justify-center
                                       hover:bg-blue-50 transition">

                                Check Another

                            </a>

                            <a
                                href="{{ route('customer.landing') }}"
                                class="h-11 px-6 rounded-xl
                                       bg-[#4f74d9] text-white
                                       font-semibold text-sm
                                       flex items-center justify-center
                                       hover:bg-[#3f63c8] transition">

                                Home

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