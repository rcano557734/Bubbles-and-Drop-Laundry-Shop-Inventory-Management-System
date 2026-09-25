@extends(auth()->user()->role === 'admin' ? 'admin.layout' : 'staff.layout')

@section('title', 'Monitor Stock Levels')

@section('content')

    <div class="mb-7">

        <h1 class="text-3xl font-bold text-[#183984]">
            Monitor Stock Levels
        </h1>

        <p class="text-sm text-blue-500 mt-1">
            Monitor the current stock level of each inventory item.
        </p>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse ($items as $item)

            @php

                $percentage = $item->max_capacity > 0
                    ? ($item->quantity / $item->max_capacity) * 100
                    : 0;

                $percentage = min($percentage, 100);

                if ($item->quantity >= $item->max_capacity) {

                    $status = 'Full';
                    $statusClass = 'bg-green-100 text-green-700';
                    $barClass = 'bg-green-500';

                } elseif ($item->quantity <= $item->reorder_level) {

                    $status = 'Low';
                    $statusClass = 'bg-red-100 text-red-700';
                    $barClass = 'bg-red-500';

                } else {

                    $status = 'Medium';
                    $statusClass = 'bg-yellow-100 text-yellow-700';
                    $barClass = 'bg-yellow-500';

                }

            @endphp


            <div class="bg-white rounded-2xl
                        border border-blue-100
                        shadow-sm p-6">

                <div class="flex justify-between items-start">

                    <div>

                        <h2 class="font-bold text-lg text-blue-900">
                            {{ $item->item_name }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            {{ $item->category }}
                        </p>

                    </div>


                    <span
                        class="px-3 py-1 rounded-full
                               text-xs font-semibold
                               {{ $statusClass }}"
                    >
                        {{ $status }}
                    </span>

                </div>


                <div class="mt-6">

                    <div class="flex justify-between text-sm mb-2">

                        <span class="text-gray-500">
                            Stock Level
                        </span>

                        <span class="font-semibold text-blue-800">
                            {{ number_format($percentage, 0) }}%
                        </span>

                    </div>


                    <div class="w-full bg-gray-200 rounded-full h-4">

                        <div
                            class="{{ $barClass }} h-4 rounded-full"
                            style="width: {{ $percentage }}%"
                        ></div>

                    </div>

                </div>


                <div class="grid grid-cols-3 gap-3 mt-6">

                    <div class="bg-gray-50 rounded-lg p-3">

                        <p class="text-xs text-gray-500">
                            Current
                        </p>

                        <p class="font-bold text-blue-900">
                            {{ $item->quantity }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-lg p-3">

                        <p class="text-xs text-gray-500">
                            Maximum
                        </p>

                        <p class="font-bold text-blue-900">
                            {{ $item->max_capacity }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-lg p-3">

                        <p class="text-xs text-gray-500">
                            Reorder
                        </p>

                        <p class="font-bold text-blue-900">
                            {{ $item->reorder_level }}
                        </p>

                    </div>

                </div>


                <p class="text-xs text-gray-500 mt-4">
                    Unit: {{ $item->unit }}
                </p>

            </div>


        @empty

            <div class="col-span-full bg-white rounded-2xl p-10 text-center text-gray-500">

                No inventory items found.

            </div>

        @endforelse

    </div>

@endsection