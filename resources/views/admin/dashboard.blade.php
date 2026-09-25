@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')

    {{-- Header --}}
    <div class="mb-7">

        <h1 class="text-3xl font-bold text-[#183984]">
            Admin Dashboard
        </h1>

        <p class="text-sm text-blue-500 mt-1">
            Good day! Here's the current inventory overview.
        </p>

    </div>


    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

        {{-- Total Items --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-blue-100">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-700 text-xl">
                    ◇
                </div>

                <div>
                    <p class="text-sm text-blue-500">
                        Total Items
                    </p>

                    <p class="text-2xl font-bold text-blue-900">
                        {{ $totalItems }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Low Stock --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-red-100">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center text-red-500 text-xl">
                    ⚠
                </div>

                <div>
                    <p class="text-sm text-blue-500">
                        Low Stock Alerts
                    </p>

                    <p class="text-2xl font-bold text-blue-900">
                        {{ $lowStockCount }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Stock In --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-green-100">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center text-green-600 text-xl">
                    ↗
                </div>

                <div>
                    <p class="text-sm text-blue-500">
                        Stock-In Today
                    </p>

                    <p class="text-2xl font-bold text-blue-900">
                        {{ $stockInToday }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Stock Out --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-yellow-100">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600 text-xl">
                    ↘
                </div>

                <div>
                    <p class="text-sm text-blue-500">
                        Stock-Out Today
                    </p>

                    <p class="text-2xl font-bold text-blue-900">
                        {{ $stockOutToday }}
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- LOW STOCK ALERT --}}
    <div class="bg-red-50 border border-red-100 rounded-2xl p-5 mb-6">

        <div class="flex items-center gap-2 mb-4">

            <span class="text-red-500">
                ⚠
            </span>

            <h2 class="font-semibold text-red-700">
                Low Stock Alert —
                {{ $lowStockCount }} item(s) need attention
            </h2>

        </div>


        @if ($lowStockItems->count())

            <div class="flex flex-wrap gap-2">

                @foreach ($lowStockItems as $item)

                    <span class="px-3 py-2 bg-white border border-red-200 text-red-700 rounded-full text-sm">
                        {{ $item->item_name }}
                    </span>

                @endforeach

            </div>

        @else

            <p class="text-sm text-green-700">
                No low-stock items at the moment.
            </p>

        @endif

    </div>


    {{-- RECENT STOCK IN --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm mb-6">

        <div class="px-5 py-4 border-b border-gray-100 flex justify-between">

            <h2 class="font-semibold text-blue-800">
                ↓ Recent Stock-In
            </h2>

            <a
                href="{{ route('admin.inventory.stock-in') }}"
                class="text-sm text-blue-600 hover:underline"
            >
                Open
            </a>

        </div>


        @forelse ($recentStockIns as $transaction)

            <div class="px-5 py-4 border-b border-gray-100 last:border-0 flex justify-between items-center">

                <div>

                    <p class="font-medium text-blue-800">
                        {{ $transaction->inventoryItem->item_name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ $transaction->supplier ?? 'No supplier' }}
                        ·
                        {{ $transaction->date->format('Y-m-d') }}
                    </p>

                </div>

                <span class="font-bold text-green-600">
                    +{{ $transaction->quantity }}
                </span>

            </div>

        @empty

            <div class="px-5 py-8 text-center text-gray-500 text-sm">
                No Stock-In records yet.
            </div>

        @endforelse

    </div>


    {{-- RECENT STOCK OUT --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm mb-6">

        <div class="px-5 py-4 border-b border-gray-100 flex justify-between">

            <h2 class="font-semibold text-blue-800">
                ↑ Recent Stock-Out
            </h2>

            <a
                href="{{ route('admin.inventory.stock-out') }}"
                class="text-sm text-blue-600 hover:underline"
            >
                Open
            </a>

        </div>


        @forelse ($recentStockOuts as $transaction)

            <div class="px-5 py-4 border-b border-gray-100 last:border-0 flex justify-between items-center">

                <div>

                    <p class="font-medium text-blue-800">
                        {{ $transaction->inventoryItem->item_name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ $transaction->reason ?? 'No reason specified' }}
                        ·
                        {{ $transaction->date->format('Y-m-d') }}
                    </p>

                </div>

                <span class="font-bold text-red-500">
                    -{{ $transaction->quantity }}
                </span>

            </div>

        @empty

            <div class="px-5 py-8 text-center text-gray-500 text-sm">
                No Stock-Out records yet.
            </div>

        @endforelse

    </div>


    {{-- SHORTCUTS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

        {{-- Manage Inventory --}}
        <a
            href="{{ route('admin.inventory.index') }}"
            class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition"
        >

            <p class="text-sm font-semibold text-blue-800">
                Manage Inventory
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Add, edit, and manage inventory items.
            </p>

        </a>


        {{-- Monitor Stock Levels --}}
        <a
            href="{{ route('admin.inventory.monitor') }}"
            class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition"
        >

            <p class="text-sm font-semibold text-blue-800">
                Monitor Stock Levels
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Check current stock levels and reorder status.
            </p>

        </a>


        {{-- Record Stock-In --}}
        <a
            href="{{ route('admin.inventory.stock-in') }}"
            class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition"
        >

            <p class="text-sm font-semibold text-blue-800">
                Record Stock-In
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Record newly received inventory.
            </p>

        </a>


        {{-- Record Stock-Out --}}
        <a
            href="{{ route('admin.inventory.stock-out') }}"
            class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition"
        >

            <p class="text-sm font-semibold text-blue-800">
                Record Stock-Out
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Record inventory used for laundry services.
            </p>

        </a>


        {{-- View Inventory --}}
        <a
            href="{{ route('admin.inventory.view') }}"
            class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition"
        >

            <p class="text-sm font-semibold text-blue-800">
                View Inventory
            </p>

            <p class="text-xs text-gray-500 mt-1">
                View current inventory records and transactions.
            </p>

        </a>


        {{-- Machines --}}
        <a
            href="{{ route('admin.machines.index') }}"
            class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition"
        >

            <p class="text-sm font-semibold text-blue-800">
                Machines
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Manage laundry machines and maintenance information.
            </p>

        </a>

    </div>

@endsection