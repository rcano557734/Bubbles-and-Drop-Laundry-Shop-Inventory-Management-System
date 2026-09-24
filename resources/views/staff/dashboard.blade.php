@extends('staff.layout')
@section('title', 'Dashboard')
@section('content')

@php
function staffLevel(array $i): string
{
if ($i['quantity'] <= $i['reorder']) {
    return 'Low' ;
    }

    if ($i['quantity'] / $i['max'] <=0.65) {
    return 'Medium' ;
    }

    return 'Full' ;
    }

    function staffBadge(string $l): string
    {
    return match ($l) { 'Low'=> '<span class="arimo px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#fef2f2;color:#dc2626;">Low</span>',
    'Medium' => '<span class="arimo px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#fffbeb;color:#d97706;">Medium</span>',
    default => '<span class="arimo px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#f0fdf4;color:#16a34a;">Full</span>',
    };
    }
    @endphp

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="arimo text-xl font-bold text-blue-900">Dashboard</h1>
            <p class="text-blue-500 text-sm mt-0.5">Welcome back, {{ session('staff_name', auth()->user()->name) }}!</p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
        ['label' => 'Total Items', 'value' => count($items), 'bg' => '#eff6ff', 'color' => '#2563eb', 'icon' => '📦'],
        ['label' => 'Low Stock Alerts', 'value' => count($lowItems), 'bg' => '#fef2f2', 'color' => '#dc2626', 'icon' => '⚠️'],
        ['label' => 'Stock-In Today', 'value' => $inToday, 'bg' => '#f0fdf4', 'color' => '#16a34a', 'icon' => '📥'],
        ['label' => 'Stock-Out Today', 'value' => $outToday, 'bg' => '#fffbeb', 'color' => '#d97706', 'icon' => '📤'],
        ] as $c)
        <div class="bg-white rounded-2xl p-5 flex items-center gap-4" style="border:1px solid #dbeafe;">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 text-2xl" @style(['background'=> $c['bg']])>{{ $c['icon'] }}</div>
            <div>
                <p class="arimo text-2xl font-bold text-blue-900">{{ $c['value'] }}</p>
                <p class="text-xs text-blue-500 leading-tight">{{ $c['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    @if (count($lowItems) > 0)
    <div class="bg-red-50 border border-red-100 rounded-2xl p-4 mb-6">
        <p class="arimo text-sm font-bold text-red-700 mb-2">⚠️ Low Stock Alert — {{ count($lowItems) }} item(s) need reorder</p>
        <div class="flex flex-wrap gap-2">
            @foreach ($lowItems as $i)
            <span class="arimo text-xs bg-white border border-red-200 text-red-700 px-3 py-1 rounded-full font-medium">
                {{ $i['name'] }} ({{ $i['quantity'] }} {{ $i['unit'] }})
            </span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded-2xl border border-blue-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-blue-50">
                <span class="arimo text-sm font-bold text-blue-800">📥 Recent Stock-In</span>
                <a href="{{ route('staff.stock-in') }}" class="text-xs text-blue-600 font-semibold hover:underline">Record →</a>
            </div>
            <div class="divide-y divide-blue-100">
                @forelse (array_values($recentIn) as $t)
                <div class="flex items-center justify-between px-5 py-3">
                    <div>
                        <p class="text-sm font-medium text-blue-800">{{ $t['item_name'] }}</p>
                        <p class="text-xs text-blue-400">{{ $t['supplier'] ?? '—' }} · {{ $t['date'] }}</p>
                    </div>
                    <span class="text-sm font-bold text-green-600">+{{ $t['quantity'] }}</span>
                </div>
                @empty
                <p class="text-sm text-blue-300 text-center py-6">No records yet</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-blue-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-blue-50">
                <span class="arimo text-sm font-bold text-blue-800">📤 Recent Stock-Out</span>
                <a href="{{ route('staff.stock-out') }}" class="text-xs text-blue-600 font-semibold hover:underline">Record →</a>
            </div>
            <div class="divide-y divide-blue-100">
                @forelse (array_values($recentOut) as $t)
                <div class="flex items-center justify-between px-5 py-3">
                    <div>
                        <p class="text-sm font-medium text-blue-800">{{ $t['item_name'] }}</p>
                        <p class="text-xs text-blue-400">{{ $t['reason'] ?? '—' }} · {{ $t['date'] }}</p>
                    </div>
                    <span class="text-sm font-bold text-orange-500">−{{ $t['quantity'] }}</span>
                </div>
                @empty
                <p class="text-sm text-blue-300 text-center py-6">No records yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach([
        ['route' => 'staff.view-inventory', 'label' => 'Manage Inventory', 'icon' => '👁'],
        ['route' => 'staff.stock-in', 'label' => 'Record Stock-In', 'icon' => '📥'],
        ['route' => 'staff.stock-out', 'label' => 'Record Stock-Out', 'icon' => '📤'],
        ['route' => 'staff.monitor-stock', 'label' => 'Monitor Stock Levels', 'icon' => '📊'],
        ] as $n)
        <a href="{{ route($n['route']) }}"
            class="bg-white border border-blue-100 rounded-2xl p-4 flex flex-col gap-2 hover:border-blue-200 hover:bg-blue-50/30 transition-all">
            <span class="text-2xl">{{ $n['icon'] }}</span>
            <span class="arimo text-xs font-semibold text-blue-800">{{ $n['label'] }}</span>
        </a>
        @endforeach
    </div>
    @endsection
