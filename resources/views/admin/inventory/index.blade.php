@extends('admin.layout')

@section('title', 'Manage Inventory')

@section('content')

    {{-- HEADER --}}
    <div class="mb-7">

        <h1 class="text-3xl font-bold text-[#183984]">
            Manage Inventory
        </h1>

        <p class="text-sm text-blue-500 mt-1">
            Manage and maintain inventory records.
        </p>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg
                    bg-green-100
                    border border-green-300
                    text-green-700
                    px-4 py-3">
            {{ session('success') }}
        </div>
    @endif


    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg
                    bg-red-100
                    border border-red-300
                    text-red-700
                    px-4 py-3">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif


    {{-- ================= ADD INVENTORY ================= --}}
    <div class="bg-white rounded-2xl
                border border-blue-100
                shadow-sm p-6 mb-6">

        <h2 class="text-lg font-semibold text-blue-800 mb-6">
            Add Inventory Item
        </h2>


        <form
            method="POST"
            action="{{ route('admin.inventory.store') }}"
            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"
        >

            @csrf


            {{-- ITEM NAME --}}
            <div>

                <label
                    for="item_name"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Item Name
                </label>

                <input
                    type="text"
                    id="item_name"
                    name="item_name"
                    value="{{ old('item_name') }}"
                    placeholder="Enter item name"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            {{-- CATEGORY --}}
            <div>

                <label
                    for="category"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Category
                </label>

                <select
                    id="category"
                    name="category"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        Select category
                    </option>

                    <option value="Detergent" @selected(old('category') === 'Detergent')}>
                        Detergent
                    </option>

                    <option value="Laundry Powder" @selected(old('category') === 'Laundry Powder')}>
                        Laundry Powder
                    </option>

                    <option value="Fabric Conditioner" @selected(old('category') === 'Fabric Conditioner')}>
                        Fabric Conditioner
                    </option>

                    <option value="Bleach" @selected(old('category') === 'Bleach')}>
                        Bleach
                    </option>

                    <option value="Stain Remover" @selected(old('category') === 'Stain Remover')}>
                        Stain Remover
                    </option>

                    <option value="Laundry Soap" @selected(old('category') === 'Laundry Soap')}>
                        Laundry Soap
                    </option>

                    <option value="Laundry Supply" @selected(old('category') === 'Laundry Supply')}>
                        Laundry Supply
                    </option>

                    <option value="Other" @selected(old('category') === 'Other')}>
                        Other
                    </option>

                </select>

            </div>


            {{-- QUANTITY --}}
            <div>

                <label
                    for="quantity"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Quantity
                </label>

                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    value="{{ old('quantity') }}"
                    placeholder="Enter quantity"
                    min="0"
                    step="1"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            {{-- MAXIMUM CAPACITY --}}
            <div>

                <label
                    for="max_capacity"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Maximum Capacity
                </label>

                <select
                    id="max_capacity"
                    name="max_capacity"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        Select maximum capacity
                    </option>

                    @for ($i = 10; $i <= 200; $i += 10)

                        <option
                            value="{{ $i }}"
                            @selected(old('max_capacity') == $i)
                        >
                            {{ $i }}
                        </option>

                    @endfor

                </select>

            </div>


            {{-- REORDER LEVEL --}}
            <div>

                <label
                    for="reorder_level"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Reorder Level
                </label>

                <select
                    id="reorder_level"
                    name="reorder_level"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        Select reorder level
                    </option>

                    @for ($i = 10; $i <= 200; $i += 10)

                        <option
                            value="{{ $i }}"
                            @selected(old('reorder_level') == $i)
                        >
                            {{ $i }}
                        </option>

                    @endfor

                </select>

            </div>


            {{-- UNIT --}}
            <div>

                <label
                    for="unit"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Unit
                </label>

                <select
                    id="unit"
                    name="unit"
                    required
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        Select unit
                    </option>

                    <option value="Piece" @selected(old('unit') === 'Piece')}>
                        Piece
                    </option>

                    <option value="Kilogram (kg)" @selected(old('unit') === 'Kilogram (kg)')}>
                        Kilogram (kg)
                    </option>

                    <option value="Liter (L)" @selected(old('unit') === 'Liter (L)')}>
                        Liter (L)
                    </option>

                    <option value="Bottle" @selected(old('unit') === 'Bottle')}>
                        Bottle
                    </option>

                    <option value="Pack" @selected(old('unit') === 'Pack')}>
                        Pack
                    </option>

                    <option value="Box" @selected(old('unit') === 'Box')}>
                        Box
                    </option>

                </select>

            </div>


            {{-- SUBMIT --}}
            <div class="md:col-span-2 xl:col-span-3">

                <button
                    type="submit"
                    class="w-full bg-blue-700
                           text-white font-semibold
                           py-3 rounded-lg
                           hover:bg-blue-800
                           transition"
                >
                    Add Item
                </button>

            </div>

        </form>

    </div>


    {{-- ================= SEARCH / FILTER ================= --}}
    <div class="bg-white rounded-2xl
                border border-blue-100
                shadow-sm p-6 mb-6">

        <form
            method="GET"
            action="{{ route('admin.inventory.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4"
        >

            {{-- SEARCH --}}
            <div>

                <label
                    for="search"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Search Item
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search item..."
                    class="w-full rounded-lg border-gray-300"
                >

            </div>


            {{-- CATEGORY FILTER --}}
            <div>

                <label
                    for="filter_category"
                    class="block text-sm font-semibold text-gray-800 mb-2"
                >
                    Category
                </label>

                <select
                    id="filter_category"
                    name="category"
                    class="w-full rounded-lg border-gray-300"
                >

                    <option value="">
                        All Categories
                    </option>

                    @foreach ($categories as $category)

                        <option
                            value="{{ $category }}"
                            @selected(request('category') === $category)
                        >
                            {{ $category }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- SEARCH BUTTON --}}
            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full bg-blue-700
                           text-white font-semibold
                           py-2.5 rounded-lg
                           hover:bg-blue-800"
                >
                    Search / Filter
                </button>

            </div>

        </form>

    </div>


    {{-- ================= INVENTORY TABLE ================= --}}
    <div class="bg-white rounded-2xl
                border border-blue-100
                shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100">

            <h2 class="text-lg font-semibold text-blue-800">
                Inventory Items
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-blue-700 text-white">

                    <tr>

                        <th class="px-5 py-4 text-left">
                            Item Name
                        </th>

                        <th class="px-5 py-4 text-left">
                            Category
                        </th>

                        <th class="px-5 py-4 text-left">
                            Quantity
                        </th>

                        <th class="px-5 py-4 text-left">
                            Status
                        </th>

                        <th class="px-5 py-4 text-left">
                            Last Updated
                        </th>

                        <th class="px-5 py-4 text-left">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($items as $item)

                        @php

                            if ($item->quantity >= $item->max_capacity) {

                                $status = 'Full';

                                $statusClass = 'bg-green-100 text-green-700';

                            } elseif ($item->quantity <= $item->reorder_level) {

                                $status = 'Low';

                                $statusClass = 'bg-red-100 text-red-700';

                            } else {

                                $status = 'Medium';

                                $statusClass = 'bg-yellow-100 text-yellow-700';

                            }

                        @endphp


                        <tr class="border-b border-gray-100">


                            {{-- ITEM NAME --}}
                            <td class="px-5 py-4 font-semibold text-blue-900">

                                {{ $item->item_name }}

                            </td>


                            {{-- CATEGORY --}}
                            <td class="px-5 py-4">

                                {{ $item->category }}

                            </td>


                            {{-- QUANTITY --}}
                            <td class="px-5 py-4">

                                {{ $item->quantity }}
                                {{ $item->unit }}

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4">

                                <span
                                    class="px-3 py-1
                                           rounded-full
                                           text-xs font-semibold
                                           {{ $statusClass }}"
                                >
                                    {{ $status }}
                                </span>

                            </td>


                            {{-- LAST UPDATED --}}
                            <td class="px-5 py-4 text-sm text-gray-500">

                                {{ $item->updated_at->format('M d, Y') }}

                            </td>


                            {{-- ACTION --}}
                            <td class="px-5 py-4">

                                <div class="flex gap-2">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.inventory.edit', $item) }}"
                                        class="px-3 py-2
                                               bg-blue-100
                                               text-blue-700
                                               rounded-lg
                                               text-sm"
                                    >
                                        Edit
                                    </a>


                                    {{-- REMOVE --}}
                                    <form
                                        method="POST"
                                        action="{{ route('admin.inventory.destroy', $item) }}"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Remove this inventory item?')"
                                            class="px-3 py-2
                                                   bg-red-100
                                                   text-red-700
                                                   rounded-lg
                                                   text-sm"
                                        >
                                            Remove
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-5 py-10
                                       text-center
                                       text-gray-500"
                            >
                                No inventory items found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection