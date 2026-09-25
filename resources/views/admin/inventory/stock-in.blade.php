@extends(auth()->user()->role === 'admin' ? 'admin.layout' : 'staff.layout')

@section('title', 'Record Stock-In')

@section('content')

    <div class="max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-7">

            <h1 class="text-3xl font-bold text-[#183984]">
                Record Stock-In
            </h1>

            <p class="text-sm text-blue-500 mt-1">
                Record newly received inventory items.
            </p>

        </div>


        {{-- ERROR MESSAGE --}}
        @if (session('error'))

            <div class="mb-6 rounded-lg
                        bg-red-100 border border-red-300
                        text-red-700 px-4 py-3">

                {{ session('error') }}

            </div>

        @elseif (session('success'))

            <div class="mb-6 rounded-lg
                        bg-green-100 border border-green-300
                        text-green-700 px-4 py-3">

                {{ session('success') }}

            </div>

        @endif


        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())

            <div class="mb-6 rounded-lg
                        bg-red-100 border border-red-300
                        text-red-700 px-4 py-3">

                <ul class="list-disc list-inside">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- FORM --}}
        <div class="bg-white rounded-2xl shadow-sm
                    border border-blue-100 p-8">

            <form
                method="POST"
                action="{{ auth()->user()->role === 'admin'
                ? route('admin.inventory.stock-in.store')
                : route('staff.inventory.stock-in.store') }}"
                class="space-y-6"
            >

                @csrf


                {{-- ITEM --}}
                <div>

                    <label
                        for="inventory_item_id"
                        class="block text-sm font-semibold
                               text-gray-700 mb-2"
                    >
                        Inventory Item
                    </label>

                    <select
                        id="inventory_item_id"
                        name="inventory_item_id"
                        required
                        class="w-full rounded-lg
                               border-gray-300 px-4 py-3"
                    >

                        <option value="">
                            Select inventory item
                        </option>

                        @foreach ($items as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(old('inventory_item_id') == $item->id)
                            >
                                {{ $item->item_name }}
                                —
                                Current:
                                {{ $item->quantity }}
                                {{ $item->unit }}
                            </option>

                        @endforeach

                    </select>

                    @error('inventory_item_id')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- QUANTITY --}}
                <div>

                    <label
                        for="quantity"
                        class="block text-sm font-semibold
                               text-gray-700 mb-2"
                    >
                        Quantity Received
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        value="{{ old('quantity') }}"
                        min="1"
                        step="1"
                        required
                        placeholder="Enter whole-number quantity"
                        class="w-full rounded-lg
                               border-gray-300 px-4 py-3"
                    >

                    <p class="mt-2 text-xs text-gray-500">
                        Stock-In quantity must be a whole number and cannot exceed the item's maximum capacity.
                    </p>

                    @error('quantity')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- DATE --}}
                <div>

                    <label
                        for="date"
                        class="block text-sm font-semibold
                               text-gray-700 mb-2"
                    >
                        Date
                    </label>

                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old('date', now()->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-lg
                               border-gray-300 px-4 py-3"
                    >

                    @error('date')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- SUPPLIER --}}
                <div>

                    <label
                        for="supplier"
                        class="block text-sm font-semibold
                               text-gray-700 mb-2"
                    >
                        Supplier
                    </label>

                    <input
                        type="text"
                        id="supplier"
                        name="supplier"
                        value="{{ old('supplier') }}"
                        required
                        placeholder="Enter supplier name"
                        class="w-full rounded-lg
                               border-gray-300 px-4 py-3"
                    >

                    @error('supplier')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- BUTTONS --}}
                <div class="flex gap-3 pt-4">

                    <button
                        type="submit"
                        class="flex-1 bg-blue-700
                               text-white font-semibold
                               py-3 rounded-lg
                               hover:bg-blue-800 transition"
                    >
                        Record Stock-In
                    </button>

                    <a
                        href="{{ auth()->user()->role === 'admin'
                            ? route('admin.dashboard')
                            : route('staff.dashboard') }}"
                        class="flex-1 text-center
                            bg-gray-200 text-gray-700
                            font-semibold py-3 rounded-lg
                            hover:bg-gray-300 transition"
                    >
                        Cancel
                    </a>
                </div>

            </form>

        </div>

    </div>

@endsection