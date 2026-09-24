@extends('staff.layout')
@section('title', 'Customer Service Activity')
@section('content')

@php
$statusStyles = [
'Washing' => ['bg' => '#fff7ed', 'border' => '#ffd6a8', 'text' => '#f54900'],
'Drying' => ['bg' => '#fff7ed', 'border' => '#ffd6a8', 'text' => '#f54900'],
'Waiting' => ['bg' => '#f1f5f9', 'border' => '#e2e8f0', 'text' => '#62748e'],
'Ready for Pickup' => ['bg' => '#faf5ff', 'border' => '#e9d4ff', 'text' => '#8200db'],
'Completed' => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#008236'],
'default' => ['bg' => '#f1f5f9', 'border' => '#e2e8f0', 'text' => '#62748e'],
];
@endphp

{{-- Page header --}}
<div class="mb-8 pb-5" style="border-bottom:0.8px solid rgba(219,234,254,0.6);">
    <p class="arimo text-xl font-bold text-[#1d293d]">Customer Service Activity</p>
    <p class="arimo text-sm text-[#62748e] mt-0.5">Monitor and manage active laundry service customers</p>
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-3 gap-4 mb-6" style="max-width:800px;">
    <div class="bg-white rounded-xl p-5 flex items-center gap-4"
        style="border:0.8px solid #dbeafe;box-shadow:0 1px 2px rgba(0,0,0,0.08);">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
            style="background:#eff6ff;font-size:18px;">📋</div>
        <div>
            <p class="arimo text-xs font-medium text-[#62748e]">Active Services</p>
            <p class="arimo text-2xl font-bold text-[#1447e6]">{{ $active }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 flex items-center gap-4"
        style="border:0.8px solid #f3e8ff;box-shadow:0 1px 2px rgba(0,0,0,0.08);">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
            style="background:#faf5ff;font-size:18px;">📦</div>
        <div>
            <p class="arimo text-xs font-medium text-[#62748e]">Ready for Pickup</p>
            <p class="arimo text-2xl font-bold text-[#8200db]">{{ $readyPick }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 flex items-center gap-4"
        style="border:0.8px solid #dcfce7;box-shadow:0 1px 2px rgba(0,0,0,0.08);">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
            style="background:#f0fdf4;font-size:18px;">✅</div>
        <div>
            <p class="arimo text-xs font-medium text-[#62748e]">Completed Today</p>
            <p class="arimo text-2xl font-bold text-[#008236]">{{ $completed }}</p>
        </div>
    </div>
</div>

{{-- Table card --}}
<div class="bg-white rounded-2xl overflow-hidden"
    style="border:0.8px solid #f1f5f9;box-shadow:0 1px 3px rgba(0,0,0,0.1),0 1px 2px -1px rgba(0,0,0,0.1);">

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-4 px-6 py-4" style="border-bottom:0.8px solid #f1f5f9;">
        <form method="GET" action="{{ route('staff.customer-service') }}"
            class="flex flex-wrap items-center gap-3 w-full">

            {{-- Search --}}
            <div class="relative" style="width:320px;">
                <button type="submit" aria-label="Search"
                    class="absolute left-3 top-2.5 text-gray-400"
                    style="background:none;border:none;padding:0;margin:0;line-height:0;cursor:pointer;">🔍</button>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search by name or service no..."
                    class="arimo w-full pl-9 pr-4 py-2 rounded-lg text-sm text-[#1d293d]
                              placeholder-[rgba(29,41,61,0.5)]"
                    style="border:0.8px solid #e2e8f0;outline:none;" />
                {{-- Keep the active filter when submitting a plain search --}}
                <input type="hidden" name="filter" value="{{ $filter ?: 'All' }}" />
            </div>

            {{-- Status filter pills --}}
            <div class="flex flex-wrap gap-2">
                @foreach(['All','Waiting','Washing','Drying','Ready for Pickup','Completed'] as $f)
                @php
                $filterStyle = ($filter ?: 'All') === $f
                ? 'background:#1e3a8a;color:#ffffff;'
                : 'background:#f1f5f9;color:#45556c;';
                @endphp
                <button type="submit" name="filter" value="{{ $f }}"
                    class="arimo px-3 py-1.5 rounded-full text-xs font-medium transition-colors"
                    style="{{ $filterStyle }}">
                    {{ $f }}
                </button>
                @endforeach
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full" style="min-width:900px;">
            <thead>
                <tr style="background:#f8fafc;">
                    @foreach(['No.','Customer Name','Service Type','Price','Kilos / Loads','Detergent','Machine','Status','Action'] as $h)
                    <th class="arimo px-6 py-3.5 text-xs font-bold uppercase tracking-wide text-[#62748e]
                                    {{ $loop->last ? 'text-right' : 'text-left' }}">
                        {{ $h }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($services as $s)
                @php
                $ss = $statusStyles[$s['status']] ?? $statusStyles['default'];
                $rowStyle = $loop->last ? '' : 'border-bottom:0.8px solid #f8fafc;';
                $statusStyle = 'background:' . $ss['bg'] . '; border:0.8px solid ' . $ss['border'] . '; color:' . $ss['text'] . ';';
                @endphp
                <tr class="hover:bg-blue-50/20 transition-colors"
                    style="{{ $rowStyle }}">

                    <td class="arimo px-6 py-4 text-base font-bold text-[#1e3a8a]">{{ $s['no'] }}</td>
                    <td class="arimo px-6 py-4 text-sm font-semibold text-[#1d293d]">{{ $s['customer'] }}</td>
                    <td class="arimo px-6 py-4 text-sm text-[#45556c]">{{ $s['service_type'] }}</td>
                    <td class="arimo px-6 py-4 text-sm font-semibold text-[#1d293d]">{{ $s['price'] }}</td>
                    <td class="arimo px-6 py-4 text-sm text-[#45556c]">{{ $s['kilos_loads'] }}</td>

                    {{-- Detergent with blue dot --}}
                    <td class="px-6 py-4">
                        <span class="arimo flex items-center gap-1.5 text-sm font-medium text-[#314158]">
                            <span class="w-2 h-2 rounded-full shrink-0"
                                style="background:#51a2ff;"></span>
                            {{ $s['detergent'] }}
                        </span>
                    </td>

                    <td class="arimo px-6 py-4 text-sm text-[#62748e]">{{ $s['machine'] }}</td>

                    {{-- Status chip --}}
                    <td class="px-6 py-4">
                        <span class="arimo inline-flex items-center px-2.5 py-0.5 rounded-full
                                        text-xs font-semibold"
                            style="{{ $statusStyle }}">
                            {{ $s['status'] }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">

                            @if(in_array($s['status'], ['Washing','Drying']))
                            <form method="POST"
                                action="{{ route('staff.service.mark-ready', $s['id']) }}">
                                @csrf
                                <button type="submit"
                                    class="arimo px-2.5 py-1.5 rounded-md text-xs font-medium
                                                       hover:opacity-80 transition-opacity"
                                    style="background:#faf5ff;color:#9810fa;">
                                    Mark Ready
                                </button>
                            </form>
                            @endif

                            @if($s['status'] === 'Ready for Pickup')
                            <form method="POST"
                                action="{{ route('staff.service.mark-done', $s['id']) }}">
                                @csrf
                                <button type="submit"
                                    class="arimo px-2.5 py-1.5 rounded-md text-xs font-medium
                                                       hover:opacity-80 transition-opacity"
                                    style="background:#f0fdf4;color:#008236;">
                                    Mark Done
                                </button>
                            </form>
                            @endif

                            {{-- Details modal trigger --}}
                            @php $modalId = 'modal-' . $s['id']; @endphp
                            <button type="button"
                                onclick="openModal('{{ $modalId }}')"
                                class="arimo flex items-center gap-1 px-2.5 py-1.5 rounded-md
                                               text-xs font-medium hover:opacity-80 transition-opacity"
                                style="background:#eff6ff;color:#155dfc;">
                                🔍 Details
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9"
                        class="arimo px-6 py-12 text-center text-sm text-[#62748e]">
                        No records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Detail modals --}}
@foreach($services as $s)
@php $modalId = 'modal-' . $s['id']; @endphp
<div id="{{ $modalId }}"
    class="hidden fixed inset-0 z-50 flex items-center justify-center"
    style="background:rgba(15,23,42,0.45);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4"
            style="border-bottom:1px solid #dbeafe;">
            <h3 class="arimo text-base font-bold text-blue-900">
                Service {{ $s['no'] }} — {{ $s['customer'] }}
            </h3>
            <button type="button" onclick="closeModal('{{ $modalId }}')"
                class="text-blue-400 hover:text-blue-800 text-xl leading-none">✕</button>
        </div>
        <div class="p-6 space-y-3">
            @foreach([
            'Customer' => $s['customer'],
            'Service Type' => $s['service_type'],
            'Price' => $s['price'],
            'Kilos / Loads'=> $s['kilos_loads'],
            'Detergent' => $s['detergent'],
            'Machine' => $s['machine'],
            'Status' => $s['status'],
            ] as $label => $value)
            <div class="flex justify-between items-center py-2
                                {{ $loop->last ? '' : 'border-b border-blue-50' }}">
                <p class="arimo text-xs font-semibold text-blue-400 uppercase tracking-wide">
                    {{ $label }}
                </p>
                <p class="arimo text-sm font-medium text-blue-900">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    // Close on backdrop click
    document.querySelectorAll('[id^="modal-"]').forEach(el => {
        el.addEventListener('click', e => {
            if (e.target === el) closeModal(el.id);
        });
    });
</script>

@endsection
