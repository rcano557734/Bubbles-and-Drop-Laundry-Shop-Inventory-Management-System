@extends(auth()->user()->role === 'admin' ? 'admin.layout' : 'staff.layout')

@section('title', 'View Inventory')

@section('content')
    {{-- HEADER --}}
    <div class="mb-7">

        <h1 class="text-3xl font-bold text-[#183984]">
            View Inventory
        </h1>

        <p class="text-sm text-blue-500 mt-1">
            View current inventory records.
        </p>

    </div>


    {{-- INVENTORY TABLE --}}
    <div class="bg-white rounded-2xl
                border border-blue-100
                shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100">

            <h2 class="text-lg font-semibold text-blue-800">
                Inventory Records
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Read-only inventory information.
            </p>

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
                            Maximum Capacity
                        </th>

                        <th class="px-5 py-4 text-left">
                            Reorder Level
                        </th>

                        <th class="px-5 py-4 text-left">
                            Unit
                        </th>

                        <th class="px-5 py-4 text-left">
                            Status
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


                        <tr class="border-b border-gray-100 hover:bg-blue-50/30">


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

                            </td>


                            {{-- MAXIMUM --}}
                            <td class="px-5 py-4">

                                {{ $item->max_capacity }}

                            </td>


                            {{-- REORDER --}}
                            <td class="px-5 py-4">

                                {{ $item->reorder_level }}

                            </td>


                            {{-- UNIT --}}
                            <td class="px-5 py-4">

                                {{ $item->unit }}

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4">

                                <span
                                    class="px-3 py-1 rounded-full
                                           text-xs font-semibold
                                           {{ $statusClass }}"
                                >
                                    {{ $status }}
                                </span>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-5 py-10
                                       text-center text-gray-500"
                            >
                                No inventory records found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection