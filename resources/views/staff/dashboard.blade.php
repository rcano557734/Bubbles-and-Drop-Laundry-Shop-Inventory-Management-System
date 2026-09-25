@extends('staff.layout')

@section('title', 'Dashboard')

@section('content')

    <!-- Header -->
    <div class="mb-7">

        <p class="text-sm font-semibold text-blue-600">
            Staff Portal
        </p>

        <h1 class="mt-1 text-3xl font-extrabold text-[#0d2a7a]">
            Dashboard
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Welcome back, {{ auth()->user()->name }}!
        </p>

    </div>


    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">

        <!-- Total Inventory -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                        Total Items
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-[#0d2a7a]">
                        {{ $totalItems }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-xl">
                    📦
                </div>

            </div>

        </div>


        <!-- Low Stock -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                        Low Stock Alerts
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-red-600">
                        {{ $lowStockCount }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center text-xl">
                    ⚠️
                </div>

            </div>

        </div>


        <!-- Stock In -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                        Stock-In Today
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-green-600">
                        {{ $stockInToday }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center text-xl">
                    📥
                </div>

            </div>

        </div>


        <!-- Stock Out -->
        <div class="bg-white rounded-2xl border border-blue-100 p-5">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                        Stock-Out Today
                    </p>

                    <p class="mt-2 text-3xl font-extrabold text-amber-600">
                        {{ $stockOutToday }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-xl">
                    📤
                </div>

            </div>

        </div>

    </div>


    <!-- Laundry Activity -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">

        <a href="{{ route('staff.orders.index') }}"
           class="bg-white rounded-2xl border border-blue-100 p-5
                  hover:bg-blue-50/40 hover:border-blue-200 transition">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Active Laundry
            </p>

            <p class="mt-2 text-3xl font-extrabold text-[#0d2a7a]">
                {{ $activeOrders }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                View customer services
            </p>

        </a>


        <a href="{{ route('staff.orders.index', ['status' => 'Received']) }}"
           class="bg-white rounded-2xl border border-blue-100 p-5
                  hover:bg-blue-50/40 hover:border-blue-200 transition">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Received
            </p>

            <p class="mt-2 text-3xl font-extrabold text-amber-600">
                {{ $receivedOrders }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Waiting for processing
            </p>

        </a>


        <a href="{{ route('staff.orders.index', ['status' => 'Washing']) }}"
           class="bg-white rounded-2xl border border-blue-100 p-5
                  hover:bg-blue-50/40 hover:border-blue-200 transition">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Washing
            </p>

            <p class="mt-2 text-3xl font-extrabold text-blue-600">
                {{ $washingOrders }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Currently washing
            </p>

        </a>


        <a href="{{ route('staff.orders.index', ['status' => 'Ready for Pickup']) }}"
           class="bg-white rounded-2xl border border-blue-100 p-5
                  hover:bg-blue-50/40 hover:border-blue-200 transition">

            <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                Ready for Pickup
            </p>

            <p class="mt-2 text-3xl font-extrabold text-green-600">
                {{ $readyOrders }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Waiting for customers
            </p>

        </a>

    </div>


    <!-- Low Stock + Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-7">

        <!-- Low Stock -->
        <section class="bg-white rounded-2xl border border-blue-100 overflow-hidden">

            <div class="px-5 py-4 border-b border-blue-100 flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-extrabold text-[#0d2a7a]">
                        Low Stock Alert
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Items that may need replenishment
                    </p>
                </div>

                <a
                    href="{{ route('staff.inventory.monitor') }}"
                    class="text-xs font-bold text-blue-600 hover:underline">

                    View →

                </a>

            </div>


            <div class="divide-y divide-blue-50">

                @forelse ($lowStockItems as $item)

                    <div class="px-5 py-4 flex items-center justify-between">

                        <div>

                            <p class="text-sm font-bold text-slate-800">
                                {{ $item->item_name }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                Reorder level: {{ $item->reorder_level }} {{ $item->unit }}
                            </p>

                        </div>

                        <span
                            class="rounded-full border border-red-200
                                   bg-red-50 px-3 py-1
                                   text-xs font-bold text-red-700">

                            {{ $item->quantity }} {{ $item->unit }}

                        </span>

                    </div>

                @empty

                    <div class="px-5 py-8 text-center">

                        <div class="text-2xl">
                            ✅
                        </div>

                        <p class="mt-2 text-sm font-semibold text-slate-600">
                            No low-stock items
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Inventory levels are currently okay.
                        </p>

                    </div>

                @endforelse

            </div>

        </section>


        <!-- Recent Stock In -->
        <section class="bg-white rounded-2xl border border-blue-100 overflow-hidden">

            <div class="px-5 py-4 border-b border-blue-100 flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-extrabold text-[#0d2a7a]">
                        Recent Stock-In
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Latest inventory additions
                    </p>
                </div>

                <a
                    href="{{ route('staff.inventory.stock-in') }}"
                    class="text-xs font-bold text-blue-600 hover:underline">

                    Record →

                </a>

            </div>


            <div class="divide-y divide-blue-50">

                @forelse ($recentStockIns as $transaction)

                    <div class="px-5 py-4 flex items-center justify-between">

                        <div>

                            <p class="text-sm font-bold text-slate-800">
                                {{ $transaction->inventoryItem->item_name }}
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                {{ $transaction->supplier ?: 'No supplier' }}
                                ·
                                {{ $transaction->date->format('M d, Y') }}
                            </p>

                        </div>

                        <span class="text-sm font-extrabold text-green-600">
                            +{{ $transaction->quantity }}
                        </span>

                    </div>

                @empty

                    <div class="px-5 py-8 text-center">

                        <p class="text-sm font-semibold text-slate-500">
                            No stock-in records yet.
                        </p>

                    </div>

                @endforelse

            </div>

        </section>

    </div>


    <!-- Quick Actions -->
    <section>

        <div class="mb-3">

            <h2 class="text-lg font-extrabold text-[#0d2a7a]">
                Quick Actions
            </h2>

        </div>


        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            <a
                href="{{ route('staff.orders.index') }}"
                class="bg-white border border-blue-100 rounded-2xl
                       p-5 hover:bg-blue-50/40 hover:border-blue-200
                       transition">

                <div class="text-2xl">
                    🧺
                </div>

                <p class="mt-3 text-sm font-bold text-blue-800">
                    Customer Service
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Manage laundry orders
                </p>

            </a>


            <a
                href="{{ route('staff.inventory.view') }}"
                class="bg-white border border-blue-100 rounded-2xl
                       p-5 hover:bg-blue-50/40 hover:border-blue-200
                       transition">

                <div class="text-2xl">
                    👁️
                </div>

                <p class="mt-3 text-sm font-bold text-blue-800">
                    View Inventory
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Check available stock
                </p>

            </a>


            <a
                href="{{ route('staff.inventory.stock-in') }}"
                class="bg-white border border-blue-100 rounded-2xl
                       p-5 hover:bg-blue-50/40 hover:border-blue-200
                       transition">

                <div class="text-2xl">
                    📥
                </div>

                <p class="mt-3 text-sm font-bold text-blue-800">
                    Record Stock-In
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Add received supplies
                </p>

            </a>


            <a
                href="{{ route('staff.inventory.stock-out') }}"
                class="bg-white border border-blue-100 rounded-2xl
                       p-5 hover:bg-blue-50/40 hover:border-blue-200
                       transition">

                <div class="text-2xl">
                    📤
                </div>

                <p class="mt-3 text-sm font-bold text-blue-800">
                    Record Stock-Out
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Record used supplies
                </p>

            </a>

        </div>

    </section>

@endsection