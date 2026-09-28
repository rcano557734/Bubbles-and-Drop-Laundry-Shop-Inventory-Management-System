@extends(auth()->user()->role === 'admin' ? 'admin.layout' : 'staff.layout')

@section('title', 'Order History')

@section('content')

<div class="mb-8 pb-5"
    style="border-bottom:0.8px solid rgba(219,234,254,0.6);">

    <p class="arimo text-xl font-bold text-[#1d293d]">
        Order History
    </p>

    <p class="arimo text-sm text-[#62748e] mt-0.5">
        View previously claimed laundry orders
    </p>
</div>


{{-- Search --}}
<div class="bg-white rounded-2xl p-5 mb-6"
    style="border:0.8px solid #f1f5f9;
           box-shadow:0 1px 3px rgba(0,0,0,0.08);">

    <form method="GET"
        action="{{ route('staff.orders.history') }}"
        class="flex flex-wrap items-center gap-3">

        <div class="relative"
            style="width:340px;">

            <span
                class="absolute left-3 top-2.5 text-gray-400">
                🔍
            </span>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search by customer or service no..."
                class="arimo w-full pl-9 pr-4 py-2.5 rounded-lg text-sm text-[#1d293d]"
                style="border:1px solid #e2e8f0;
                       outline:none;"
            >
        </div>

        <button
            type="submit"
            class="arimo px-4 py-2.5 rounded-lg text-sm font-semibold text-white"
            style="background:#1e3a8a;"
        >
            Search
        </button>

        @if($search !== '')
            <a
                href="{{ route('staff.orders.history') }}"
                class="arimo px-4 py-2.5 rounded-lg text-sm font-medium"
                style="background:#f1f5f9;color:#45556c;"
            >
                Clear
            </a>
        @endif

    </form>
</div>


{{-- Summary --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <div class="bg-white rounded-xl p-5"
        style="border:0.8px solid #dbeafe;
               box-shadow:0 1px 2px rgba(0,0,0,0.08);">

        <p class="arimo text-xs font-medium text-[#62748e]">
            Claimed Orders
        </p>

        <p class="arimo text-2xl font-bold text-[#1447e6] mt-1">
            {{ $orders->count() }}
        </p>

    </div>

</div>


{{-- Table --}}
<div class="bg-white rounded-2xl overflow-hidden"
    style="border:0.8px solid #f1f5f9;
           box-shadow:0 1px 3px rgba(0,0,0,0.1);">

    <div class="px-6 py-4"
        style="border-bottom:0.8px solid #f1f5f9;">

        <p class="arimo text-sm font-bold text-[#1d293d]">
            Completed Laundry Records
        </p>

        <p class="arimo text-xs text-[#62748e] mt-0.5">
            Orders that have already been claimed by customers
        </p>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full"
            style="min-width:1100px;">

            <thead>

                <tr style="background:#f8fafc;">

                    @foreach([
                        'Service No.',
                        'Customer',
                        'Service',
                        'Weight',
                        'Loads',
                        'Total',
                        'Machine',
                        'Claimed Date',
                        'Action'
                    ] as $heading)

                        <th
                            class="arimo px-6 py-3.5 text-xs font-bold uppercase tracking-wide text-[#62748e]
                            {{ $heading === 'Action' ? 'text-right' : 'text-left' }}"
                        >
                            {{ $heading }}
                        </th>

                    @endforeach

                </tr>

            </thead>


            <tbody>

                @forelse($orders as $order)

                    <tr
                        class="hover:bg-blue-50/20 transition-colors"
                        style="{{ !$loop->last
                            ? 'border-bottom:0.8px solid #f8fafc;'
                            : '' }}"
                    >

                        {{-- Service Number --}}
                        <td class="arimo px-6 py-4 text-sm font-bold text-[#1e3a8a]">
                            {{ $order->service_number }}
                        </td>


                        {{-- Customer --}}
                        <td class="arimo px-6 py-4 text-sm font-semibold text-[#1d293d]">
                            {{ $order->customer->full_name }}
                        </td>


                        {{-- Service --}}
                        <td class="arimo px-6 py-4 text-sm text-[#45556c]">
                            {{ $order->service->service_name }}
                        </td>


                        {{-- Weight --}}
                        <td class="arimo px-6 py-4 text-sm text-[#45556c]">
                            {{ $order->kilos !== null
                                ? number_format((float) $order->kilos, 2) . ' kg'
                                : '—' }}
                        </td>


                        {{-- Loads --}}
                        <td class="arimo px-6 py-4 text-sm text-[#45556c]">
                            {{ $order->load_count > 0
                                ? $order->load_count . ' ' .
                                  ($order->load_count === 1 ? 'load' : 'loads')
                                : '—' }}
                        </td>


                        {{-- Total --}}
                        <td class="arimo px-6 py-4 text-sm font-semibold text-[#1d293d]">
                            ₱{{ number_format((float) $order->total_amount, 2) }}
                        </td>


                        {{-- Machine --}}
                        <td class="arimo px-6 py-4 text-sm text-[#62748e]">
                            {{ $order->machine?->machine_name ?? '—' }}
                        </td>


                        {{-- Claimed Date --}}
                        <td class="arimo px-6 py-4 text-sm text-[#62748e]">
                            {{ $order->completed_at
                                ? $order->completed_at->format('M d, Y h:i A')
                                : '—' }}
                        </td>


                        {{-- Action --}}
                        <td class="arimo px-6 py-4 text-right">

                            <form
                                method="POST"
                                action="{{ route('staff.orders.undo-claim', $order) }}"
                                onsubmit="return confirm(
                                    'Undo claim for {{ addslashes($order->customer->full_name) }}?\n\n' +
                                    'Service No.: {{ addslashes($order->service_number) }}\n\n' +
                                    'This will return the laundry to Ready for Pickup.'
                                );"
                                class="inline"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-xs font-bold text-white transition hover:opacity-90"
                                    style="background:#f59e0b;"
                                >
                                    Undo Claim
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9"
                            class="arimo px-6 py-12 text-center text-sm text-[#62748e]">

                            No claimed laundry orders found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection