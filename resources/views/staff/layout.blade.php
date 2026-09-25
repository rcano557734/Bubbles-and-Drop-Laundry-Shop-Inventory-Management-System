<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Staff Portal') — Bubbles & Drop
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#eaf1ff]">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside class="sticky top-0 h-screen w-72 shrink-0 bg-[#1e3a8a] text-white shadow-xl flex flex-col">

            {{-- BRAND --}}
            <div class="px-6 py-7 border-b border-blue-700/60">

                <h1 class="text-xl font-extrabold">
                    Bubbles &amp; Drop
                </h1>

                <p class="mt-1 text-sm text-blue-200">
                    Laundry Shop
                </p>

                <div class="mt-4 rounded-lg bg-blue-900/50 border border-blue-700/50 px-3 py-2">

                    <div class="flex items-center gap-2">

                        <span class="w-2 h-2 rounded-full bg-green-400"></span>

                        <span class="text-xs font-semibold text-blue-100">
                            Staff / Partner Portal
                        </span>

                    </div>

                </div>

            </div>


            {{-- NAVIGATION --}}
            <nav class="flex-1 px-4 py-5 overflow-y-auto">

                <p class="px-3 mb-3 text-xs font-bold uppercase tracking-widest text-blue-300/70">
                    Main
                </p>


                {{-- DASHBOARD --}}
                <a
                    href="{{ route('staff.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold mb-1 transition
                        {{ request()->routeIs('staff.dashboard')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span>🏠</span>
                    <span>Dashboard</span>

                </a>


                {{-- CUSTOMER SERVICE --}}
                <a
                    href="{{ route('staff.orders.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold mb-1 transition
                        {{ request()->routeIs('staff.orders.*')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span>🧺</span>
                    <span>Customer Service</span>

                </a>


                <p class="px-3 mt-7 mb-3 text-xs font-bold uppercase tracking-widest text-blue-300/70">
                    Inventory
                </p>


                {{-- VIEW INVENTORY --}}
                <a
                    href="{{ route('staff.inventory.view') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold mb-1 transition
                        {{ request()->routeIs('staff.inventory.view')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span>👁️</span>
                    <span>View Inventory</span>

                </a>


                {{-- MONITOR --}}
                <a
                    href="{{ route('staff.inventory.monitor') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold mb-1 transition
                        {{ request()->routeIs('staff.inventory.monitor')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span>📊</span>
                    <span>Monitor Stock</span>

                </a>


                {{-- STOCK IN --}}
                <a
                    href="{{ route('staff.inventory.stock-in') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold mb-1 transition
                        {{ request()->routeIs('staff.inventory.stock-in')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span>📥</span>
                    <span>Record Stock-In</span>

                </a>


                {{-- STOCK OUT --}}
                <a
                    href="{{ route('staff.inventory.stock-out') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition
                        {{ request()->routeIs('staff.inventory.stock-out')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span>📤</span>
                    <span>Record Stock-Out</span>

                </a>

            </nav>


            {{-- USER / LOGOUT --}}
            <div class="border-t border-blue-700/60 px-4 py-4">

                <div class="px-4 mb-3">

                    <p class="text-xs text-blue-300">
                        Signed in as
                    </p>

                    <p class="text-sm font-bold text-white truncate">
                        {{ auth()->user()->name }}
                    </p>

                </div>


                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg
                               text-sm font-semibold text-blue-100
                               hover:bg-white/10 hover:text-white transition"
                    >

                        <span>🚪</span>
                        <span>Sign Out</span>

                    </button>

                </form>

            </div>

        </aside>


        {{-- MAIN --}}
        <main class="flex-1 min-w-0 overflow-y-auto">

            {{-- MOBILE TOP BAR --}}
            <div class="md:hidden bg-[#1e3a8a] text-white px-5 py-4">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="font-bold">
                            Bubbles &amp; Drop
                        </p>

                        <p class="text-xs text-blue-200">
                            Staff Portal
                        </p>

                    </div>

                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <button class="text-sm font-semibold">
                            Sign Out
                        </button>

                    </form>

                </div>

            </div>


            {{-- SUCCESS TOAST --}}
            @if (session('success'))

                <div
                    id="successToast"
                    class="fixed top-5 right-5 z-50
                           rounded-xl bg-green-600
                           px-5 py-3
                           text-sm font-semibold text-white
                           shadow-xl"
                >

                    {{ session('success') }}

                </div>

                <script>
                    setTimeout(() => {
                        document.getElementById('successToast')?.remove();
                    }, 3000);
                </script>

            @endif


            <div class="max-w-7xl mx-auto px-5 sm:px-8 py-7">

                @yield('content')

            </div>

        </main>

    </div>

</body>

</html>