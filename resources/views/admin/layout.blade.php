<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Portal') — Bubbles & Drop
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#edf4ff]">

    <div class="flex min-h-screen">

        {{-- ================= SIDEBAR ================= --}}
        <aside class="sticky top-0 h-screen w-64 shrink-0 bg-[#233f91] text-white flex flex-col">

            {{-- BRAND --}}
            <div class="px-6 py-6 border-b border-blue-400/20">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg bg-white/15 flex items-center justify-center">
                        <span class="text-xl">◉</span>
                    </div>

                    <div>
                        <h1 class="font-bold text-lg">
                            Bubbles & Drop
                        </h1>

                        <p class="text-xs text-blue-200">
                            Laundry Shop
                        </p>
                    </div>

                </div>

            </div>

            {{-- ADMIN ACCOUNT --}}
            <div class="px-4 pt-4">

                <div class="bg-white/10 rounded-lg px-4 py-3">

                    <div class="flex items-center gap-3">

                        <div class="w-8 h-8 rounded-full bg-white text-blue-800 flex items-center justify-center font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0">

                            <p class="text-sm font-semibold">
                                Admin Account
                            </p>

                            <p class="text-xs text-blue-200">
                                Admin Portal
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- NAVIGATION --}}
            <nav class="px-4 mt-6 flex-1 overflow-y-auto">

                <p class="text-xs uppercase tracking-widest text-blue-300 px-3 mb-3">
                    Modules
                </p>


                {{-- DASHBOARD --}}
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg mb-1 transition
                        {{ request()->routeIs('admin.dashboard')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span class="w-5 text-center">▥</span>

                    <span class="text-sm font-medium">
                        Dashboard
                    </span>

                </a>


                {{-- MANAGE INVENTORY --}}
                <a
                    href="{{ route('admin.inventory.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg mb-1 transition
                        {{ request()->routeIs('admin.inventory.index', 'admin.inventory.edit')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span class="w-5 text-center">▣</span>

                    <span class="text-sm font-medium">
                        Manage Inventory
                    </span>

                </a>


                {{-- MONITOR STOCK --}}
                <a
                    href="{{ route('admin.inventory.monitor') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg mb-1 transition
                        {{ request()->routeIs('admin.inventory.monitor')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span class="w-5 text-center">⌁</span>

                    <span class="text-sm font-medium">
                        Monitor Stock Levels
                    </span>

                </a>


                {{-- STOCK IN --}}
                <a
                    href="{{ route('admin.inventory.stock-in') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg mb-1 transition
                        {{ request()->routeIs('admin.inventory.stock-in')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span class="w-5 text-center">↓</span>

                    <span class="text-sm font-medium">
                        Record Stock-In
                    </span>

                </a>


                {{-- STOCK OUT --}}
                <a
                    href="{{ route('admin.inventory.stock-out') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg mb-1 transition
                        {{ request()->routeIs('admin.inventory.stock-out')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span class="w-5 text-center">↑</span>

                    <span class="text-sm font-medium">
                        Record Stock-Out
                    </span>

                </a>


                {{-- VIEW INVENTORY --}}
                <a
                    href="{{ route('admin.inventory.view') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg mb-1 transition
                        {{ request()->routeIs('admin.inventory.view')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span class="w-5 text-center">◉</span>

                    <span class="text-sm font-medium">
                        View Inventory
                    </span>

                </a>


                {{-- MACHINES --}}
                <a
                    href="{{ route('admin.machines.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-lg transition
                        {{ request()->routeIs('admin.machines.*')
                            ? 'bg-white/15 text-white'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                >

                    <span class="w-5 text-center">⚙</span>

                    <span class="text-sm font-medium">
                        Machines
                    </span>

                </a>

            </nav>


            {{-- LOGOUT --}}
            <div class="px-4 py-5 border-t border-blue-400/20">

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-lg
                               text-blue-100 hover:bg-white/10 hover:text-white
                               transition"
                    >

                        <span class="w-5 text-center">
                            ↪
                        </span>

                        <span class="text-sm font-medium">
                            Sign Out
                        </span>

                    </button>

                </form>

            </div>

        </aside>


        {{-- ================= MAIN CONTENT ================= --}}
        <main class="flex-1 min-w-0 overflow-y-auto">

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


            {{-- PAGE CONTENT --}}
            <div class="max-w-7xl mx-auto px-5 sm:px-8 py-7">

                @yield('content')

            </div>

        </main>

    </div>

</body>

</html>