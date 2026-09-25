@extends('staff.layout')

@section('title', 'Customer Service Activity')

@section('content')

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-7">

    <a
        href="{{ route('staff.orders.create') }}"
        class="inline-flex items-center justify-center rounded-xl bg-[#4f74d9] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#3f63c8]"
    >
        + Add Laundry Order
    </a>

        <div>

            <p class="text-sm font-semibold text-blue-600">
                Laundry Operations
            </p>

            <h1 class="mt-1 text-3xl font-extrabold text-[#0d2a7a]">
                Customer Service Activity
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Manage customer laundry orders and update their processing status.
            </p>

        </div>

    </div>


    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">

        <!-- Active -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Active Orders
            </p>

            <p class="mt-2 text-3xl font-extrabold text-[#0d2a7a]">
                {{ $totalActive }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Orders currently being processed
            </p>

        </div>


        <!-- Received -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Received
            </p>

            <p class="mt-2 text-3xl font-extrabold text-amber-600">
                {{ $statusCounts->get('Received', 0) }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Waiting to be processed
            </p>

        </div>


        <!-- In Progress -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                In Progress
            </p>

            <p class="mt-2 text-3xl font-extrabold text-blue-600">
                {{
                    $statusCounts->get('Washing', 0)
                    + $statusCounts->get('Drying', 0)
                    + $statusCounts->get('Folding', 0)
                }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Washing, drying, or folding
            </p>

        </div>


        <!-- Ready -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Ready for Pickup
            </p>

            <p class="mt-2 text-3xl font-extrabold text-green-600">
                {{ $readyCount }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Customers can claim their laundry
            </p>

        </div>

    </div>


    <!-- Search / Filter -->
    <section class="bg-white rounded-2xl border border-blue-100 p-5 mb-5">

        <form method="GET" action="{{ route('staff.orders.index') }}">

            <div class="grid grid-cols-1 md:grid-cols-[1fr_220px_auto] gap-3">

                <!-- Search -->
                <div>

                    <label
                        for="search"
                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600">

                        Search

                    </label>

                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search service number or customer name..."
                        class="w-full rounded-xl border border-blue-100
                               bg-white px-4 py-3 text-sm text-slate-800
                               placeholder:text-slate-400
                               outline-none
                               focus:border-blue-500
                               focus:ring-2 focus:ring-blue-200">

                </div>


                <!-- Status -->
                <div>

                    <label
                        for="status"
                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-blue-600">

                        Status

                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-xl border border-blue-100
                               bg-white px-4 py-3 text-sm text-slate-800
                               outline-none
                               focus:border-blue-500
                               focus:ring-2 focus:ring-blue-200">

                        <option value="All" {{ $status === 'All' ? 'selected' : '' }}>
                            All Active
                        </option>

                        <option value="Received" {{ $status === 'Received' ? 'selected' : '' }}>
                            Received
                        </option>

                        <option value="Washing" {{ $status === 'Washing' ? 'selected' : '' }}>
                            Washing
                        </option>

                        <option value="Drying" {{ $status === 'Drying' ? 'selected' : '' }}>
                            Drying
                        </option>

                        <option value="Folding" {{ $status === 'Folding' ? 'selected' : '' }}>
                            Folding
                        </option>

                        <option value="Ready for Pickup" {{ $status === 'Ready for Pickup' ? 'selected' : '' }}>
                            Ready for Pickup
                        </option>

                    </select>

                </div>


                <!-- Button -->
                <div class="flex items-end">

                    <button
                        type="submit"
                        class="w-full md:w-auto h-[46px]
                               px-6 rounded-xl
                               bg-[#4f74d9]
                               text-white
                               text-sm font-bold
                               hover:bg-[#3f63c8]
                               transition">

                        Search

                    </button>

                </div>

            </div>

        </form>

    </section>


    <!-- Orders -->
    <section class="bg-white rounded-2xl border border-blue-100 overflow-hidden">

        <div class="px-5 py-4 border-b border-blue-100 flex items-center justify-between">

            <div>

                <h2 class="text-lg font-extrabold text-[#0d2a7a]">
                    Laundry Orders
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    {{ $orders->count() }} order(s) displayed
                </p>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px]">

                <thead class="bg-[#eff6ff]">

                    <tr class="text-left">

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Service No.
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Customer
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Service
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Weight
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Loads
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Total
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Status
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-blue-50">

                    @forelse ($orders as $order)

                        @php

                            $statusClasses = match ($order->status) {

                                'Received' =>
                                    'bg-amber-50 text-amber-700 border-amber-200',

                                'Washing' =>
                                    'bg-blue-50 text-blue-700 border-blue-200',

                                'Drying' =>
                                    'bg-indigo-50 text-indigo-700 border-indigo-200',

                                'Folding' =>
                                    'bg-purple-50 text-purple-700 border-purple-200',

                                'Ready for Pickup' =>
                                    'bg-green-50 text-green-700 border-green-200',

                                default =>
                                    'bg-slate-50 text-slate-700 border-slate-200',

                            };

                        @endphp


                        <tr class="hover:bg-blue-50/40 transition">


                            <!-- Service Number -->
                            <td class="px-5 py-4">

                                <p class="font-bold text-[#0d2a7a]">
                                    {{ $order->service_number }}
                                </p>

                            </td>


                            <!-- Customer -->
                            <td class="px-5 py-4">

                                <p class="font-semibold text-slate-800">
                                    {{ $order->customer->full_name }}
                                </p>

                                @if ($order->customer->contact_number)

                                    <p class="mt-0.5 text-xs text-slate-400">
                                        {{ $order->customer->contact_number }}
                                    </p>

                                @endif

                            </td>


                            <!-- Service -->
                            <td class="px-5 py-4">

                                <p class="text-sm font-medium text-slate-700">
                                    {{ $order->service->service_name }}
                                </p>

                            </td>


                            <!-- Weight -->
                            <td class="px-5 py-4">

                                @if ($order->kilos !== null)

                                    <p class="font-semibold text-slate-800">
                                        {{ number_format($order->kilos, 2) }} kg
                                    </p>

                                @else

                                    <span class="text-xs font-semibold text-slate-400">
                                        Pending weighing
                                    </span>

                                @endif

                            </td>


                            <!-- Loads -->
                            <td class="px-5 py-4">

                                @if ($order->load_count > 0)

                                    <p class="font-semibold text-slate-800">
                                        {{ $order->load_count }}
                                    </p>

                                @else

                                    <span class="text-xs font-semibold text-slate-400">
                                        Pending
                                    </span>

                                @endif

                            </td>


                            <!-- Total -->
                            <td class="px-5 py-4">

                                @if ($order->total_amount > 0)

                                    <p class="font-bold text-slate-800">
                                        ₱{{ number_format($order->total_amount, 2) }}
                                    </p>

                                @else

                                    <span class="text-xs font-semibold text-slate-400">
                                        Pending
                                    </span>

                                @endif

                            </td>


                            <!-- Status -->
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex items-center rounded-full border
                                           px-3 py-1 text-xs font-bold
                                           {{ $statusClasses }}">

                                    {{ $order->status }}

                                </span>

                            </td>


                            <!-- Action -->
                            <td class="px-5 py-4">

                                <a
                                    href="{{ route('staff.orders.edit', $order) }}"
                                    class="inline-flex items-center justify-center
                                           rounded-lg
                                           bg-[#4f74d9]
                                           px-4 py-2
                                           text-xs font-bold text-white
                                           hover:bg-[#3f63c8]
                                           transition">

                                    Manage

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-5 py-16 text-center">

                                <div class="mx-auto w-14 h-14 rounded-2xl
                                            bg-blue-50
                                            flex items-center justify-center
                                            text-2xl">

                                    🧺

                                </div>

                                <p class="mt-4 font-bold text-slate-700">
                                    No laundry orders found
                                </p>

                                <p class="mt-1 text-sm text-slate-400">
                                    Try changing your search or status filter.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

@endsection