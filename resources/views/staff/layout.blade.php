<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Staff Portal') — Bubbles & Drop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #eaf1ff;
        }

        .arimo {
            font-family: 'Arimo', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden" style="background:#eaf1ff;">

    {{-- Sidebar --}}
    <aside class="flex flex-col h-screen shrink-0 shadow-xl" style="width:288px;background:#1e3a8a;">
        {{-- Brand --}}
        <div class="px-6 pt-7 pb-5" style="border-bottom:0.8px solid rgba(25,60,184,0.6);">
            <p class="arimo text-xl font-bold text-white leading-tight">Bubbles &amp; Drop</p>
            <p class="arimo text-base font-medium leading-6 mt-0.5" style="color:#8ec5ff;">Laundry Shop</p>
            <div class="mt-3 flex items-center gap-1.5 px-2.5 py-1.5 rounded-md" style="background:rgba(25,60,184,0.5);border:0.8px solid rgba(20,71,230,0.5);">
                <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#05df72;"></span>
                <p class="arimo text-xs font-medium tracking-wide" style="color:#bedbff;">Staff Account — Partner Portal</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 flex flex-col px-4 py-4 overflow-y-auto">
            <p class="arimo text-xs font-semibold uppercase tracking-widest px-3 mb-2" style="color:rgba(81,162,255,0.7);">Modules</p>
            @php $cur = Route::currentRouteName(); @endphp

            @php
            $mainNav = [
            ['route'=>'staff.dashboard','label'=>'Dashboard','icon'=>'🏠'],
            ['route'=>'staff.customer-service','label'=>'Customer Service Activity','icon'=>'🧺'],
            ];
            $invNav = [
            ['route'=>'staff.view-inventory', 'label'=>'Manage Inventory', 'icon'=>'👁'],
            ['route'=>'staff.monitor-stock', 'label'=>'Monitor Stock Levels', 'icon'=>'📊'],
            ['route'=>'staff.stock-in', 'label'=>'Record Stock-In', 'icon'=>'📥'],
            ['route'=>'staff.stock-out', 'label'=>'Record Stock-Out', 'icon'=>'📤'],
            ];
            @endphp

            @foreach($mainNav as $nav)
            @php $active = $cur === $nav['route']; @endphp
            <a href="{{ route($nav['route']) }}"
                aria-current="{{ $active ? 'page' : 'false' }}"
                class="arimo flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all mb-0.5 {{ $active ? 'bg-white/15 text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                @if($active)<span class="w-1.5 h-1.5 rounded-full bg-white shrink-0" aria-hidden="true"></span>@endif
                <span>{{ $nav['icon'] }}</span>
                {{ $nav['label'] }}
            </a>
            @endforeach

            <p class="arimo text-xs font-semibold uppercase tracking-widest px-3 mt-5 mb-2" style="color:rgba(81,162,255,0.7);">Inventory</p>

            @foreach($invNav as $nav)
            @php $active = $cur === $nav['route']; @endphp
            <a href="{{ route($nav['route']) }}"
                aria-current="{{ $active ? 'page' : 'false' }}"
                class="arimo flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all mb-0.5 {{ $active ? 'bg-white/15 text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                @if($active)<span class="w-1.5 h-1.5 rounded-full bg-white shrink-0" aria-hidden="true"></span>@endif
                <span>{{ $nav['icon'] }}</span>
                {{ $nav['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <div class="px-4 py-4" style="border-top:0.8px solid rgba(25,60,184,0.6);">
            <form method="POST" action="{{ route('staff.logout') }}">
                @csrf
                <button type="submit" class="arimo flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium w-full text-left transition-all"
                    style="color:#8ec5ff;"
                    onmouseover="this.style.background='rgba(255,255,255,0.08)'"
                    onmouseout="this.style.background='transparent'">
                    🚪 Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 overflow-y-auto">
        @if(session('toast'))
        <div class="fixed top-4 right-4 z-50 bg-green-600 text-white px-5 py-3 rounded-xl text-sm font-semibold shadow-lg" id="toast">
            {{ session('toast') }}
        </div>
        <script>
            setTimeout(() => document.getElementById('toast')?.remove(), 3000)
        </script>
        @endif

        <div class="max-w-7xl mx-auto px-8 py-8">
            @yield('content')
        </div>
    </main>
</body>

</html>
