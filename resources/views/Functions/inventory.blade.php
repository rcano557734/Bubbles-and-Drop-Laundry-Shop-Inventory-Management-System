@extends('staff.layout')
@section('title','Manage Inventory')
@section('content')

@php
function sLevel(array $i): string {
if ($i['quantity'] <= $i['reorder']) return 'Low' ;
    if ($i['quantity'] / $i['max'] <=0.65) return 'Medium' ;
    return 'Full' ;
    }
    function sBadgeHtml(string $l): string {
    return match($l) { 'Low'=> '<span class="arimo px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#fef2f2;color:#dc2626;">Low</span>',
    'Medium' => '<span class="arimo px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#fffbeb;color:#d97706;">Medium</span>',
    default => '<span class="arimo px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#f0fdf4;color:#16a34a;">Full</span>',
    };
    }
    @endphp

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="arimo text-xl font-bold text-blue-900">Manage Inventory</h1>
            <p class="text-blue-500 text-sm mt-0.5">Manage and review current inventory records.</p>
        </div>
    </div>

    {{-- Search + filter --}}
    <form method="GET" action="{{ route('staff.view-inventory') }}" class="flex flex-wrap gap-3 mb-6">
        <div class="flex items-center gap-2 bg-white border border-blue-200 rounded-xl px-3 py-2 flex-1 min-w-48">
            <span class="text-blue-300 shrink-0">🔍</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search items…"
                class="flex-1 text-sm bg-transparent text-blue-900 placeholder-blue-300 outline-none" />
        </div>
        <div class="flex gap-2 flex-wrap">
            <button type="submit" name="category" value="All"
                class="arimo px-3 py-2 rounded-xl text-xs font-semibold border transition-all {{ $catFilter === 'All' ? 'bg-blue-700 text-white border-blue-700' : 'bg-white text-blue-700 border-blue-200 hover:border-blue-400' }}">All</button>
            @foreach($categories as $c)
            <button type="submit" name="category" value="{{ $c }}"
                class="arimo px-3 py-2 rounded-xl text-xs font-semibold border transition-all {{ $catFilter === $c ? 'bg-blue-700 text-white border-blue-700' : 'bg-white text-blue-700 border-blue-200 hover:border-blue-400' }}">{{ $c }}</button>
            @endforeach
        </div>
    </form>

    <div class="bg-white border border-blue-100 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-50">
                <tr class="text-blue-400 text-xs uppercase tracking-wider">
                    @foreach(['Item Name','Category','Unit','Qty','Status','Last Updated',''] as $h)
                    <th class="text-left px-5 py-3 font-semibold">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse($items as $item)
                @php $level = sLevel($item); @endphp
                <tr class="hover:bg-blue-50/40 transition-colors">
                    <td class="px-5 py-3.5 font-semibold text-blue-900">{{ $item['name'] }}</td>
                    <td class="px-5 py-3.5 text-blue-500">{{ $item['category'] }}</td>
                    <td class="px-5 py-3.5 text-blue-400">{{ $item['unit'] }}</td>
                    <td class="px-5 py-3.5 font-bold text-blue-900">{{ $item['quantity'] }}</td>
                    <td class="px-5 py-3.5">{!! sBadgeHtml($level) !!}</td>
                    <td class="px-5 py-3.5 text-blue-400">{{ $item['updated'] }}</td>
                    <td class="px-5 py-3.5">
                        <button type="button" data-open-modal="{{ $item['id'] }}"
                            class="text-xs text-blue-500 font-semibold hover:text-blue-800 hover:underline">View Receipt</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-blue-300">No items found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modals --}}
    @foreach($items as $item)
    @php
    $level = sLevel($item);
    $pct = min($item['quantity'] / $item['max'], 1) * 100;
    $txns = array_filter($transactions, fn($t) => $t['item_id'] === $item['id']);
    @endphp
    <div id="modal-{{ $item['id'] }}" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(15,23,42,0.45);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 sticky top-0 bg-white" style="border-bottom:1px solid #dbeafe;">
                <h3 class="arimo text-base font-bold text-blue-900">{{ $item['name'] }}</h3>
                <button type="button" data-close-modal="{{ $item['id'] }}" class="text-blue-400 hover:text-blue-800 text-xl">✕</button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-3 gap-4 mb-4">
                    @foreach([['Current',$item['quantity'],'#2563eb'],['Reorder At',$item['reorder'],'#d97706'],['Max',$item['max'],'#16a34a']] as [$k,$v,$col])
                    <div>
                        <p class="text-xs font-semibold text-blue-400 uppercase tracking-widest">{{ $k }}</p>
                        <p class="text-2xl font-bold mt-0.5" @style(['color'=> $col])>{{ $v }}</p>
                        <p class="text-xs text-blue-400">{{ $item['unit'] }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="h-2 rounded-full bg-blue-100 overflow-hidden mb-4">
                    <div class="h-full rounded-full bg-blue-500" @style(['width'=> $pct . '%'])></div>
                </div>
                <p class="arimo text-sm font-bold text-blue-800 mb-3 border-t border-blue-50 pt-4">Stock Movement History</p>
                <div class="divide-y divide-blue-50">
                    @forelse(array_reverse(array_values($txns)) as $t)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-sm {{ $t['type']==='in' ? 'bg-green-100' : 'bg-orange-100' }}">
                                {{ $t['type']==='in' ? '📥' : '📤' }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-800">{{ $t['type']==='in' ? 'Stock In' : 'Stock Out' }}</p>
                                <p class="text-xs text-blue-400">{{ $t['type']==='in' ? ($t['supplier'] ?? '—') : ($t['reason'] ?? '—') }} · {{ $t['date'] }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold {{ $t['type']==='in' ? 'text-green-600' : 'text-orange-500' }}">
                            {{ $t['type']==='in' ? '+' : '−' }}{{ $t['quantity'] }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-blue-300 text-center py-4">No transactions yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        document.querySelectorAll('[data-open-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById('modal-' + button.dataset.openModal);
                if (modal) modal.classList.remove('hidden');
            });
        });

        document.querySelectorAll('[data-close-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById('modal-' + button.dataset.closeModal);
                if (modal) modal.classList.add('hidden');
            });
        });
    </script>
    @endsection
