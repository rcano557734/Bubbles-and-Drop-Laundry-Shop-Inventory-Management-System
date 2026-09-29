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
                <a href="{{ route('staff.orders.index') }}"
                    class="flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg
                        arimo text-sm font-medium transition-colors
                        {{ request()->routeIs('staff.orders.index')
                                ? 'bg-blue-100 text-blue-800'
                                : 'text-slate-600 hover:bg-slate-100' }}">

                    <span class="flex items-center gap-3">
                        <span>🧺</span>
                        <span>Customer Service</span>
                    </span>

                    <span
                        id="customer-order-badge"
                        class="hidden min-w-[22px] h-[22px] px-1.5
                            rounded-full bg-red-500 text-white
                            text-[11px] font-bold
                            items-center justify-center"
                    >
                        0
                    </span>

                </a>

                <a href="{{ route('staff.orders.history') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg
                        arimo text-sm font-medium transition-colors
                        {{ request()->routeIs('staff.orders.history')
                                ? 'bg-blue-100 text-blue-800'
                                : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>📜</span>
                    <span>Order History</span>
                </a>

                {{-- MACHINE MONITORING --}}
                <a
                    href="{{ route('staff.machines.monitor') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg
                        arimo text-sm font-medium transition-colors
                        {{ request()->routeIs('staff.machines.monitor')
                                ? 'bg-blue-100 text-blue-800'
                                : 'text-slate-600 hover:bg-slate-100' }}"
                >
                    <span>⚙️</span>
                    <span>Machine Monitoring</span>
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

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const badge = document.getElementById('customer-order-badge');

        if (!badge) {
            return;
        }

        let previousCount = null;

        function showCustomerNotification(order) {

            if (!order) {
                return;
            }

            const existing = document.getElementById(
                'customer-order-notification'
            );

            if (existing) {
                existing.remove();
            }

            const notification = document.createElement('div');

            notification.id = 'customer-order-notification';

            notification.className =
                'fixed top-5 right-5 z-[9999] w-[360px] bg-white rounded-2xl shadow-2xl border border-blue-100 overflow-hidden';

            notification.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start gap-3">

                        <div class="w-10 h-10 rounded-full bg-blue-50
                                    flex items-center justify-center text-xl shrink-0">
                            🔔
                        </div>

                        <div class="flex-1">

                            <div class="flex items-start justify-between gap-3">

                                <div>
                                    <p class="text-sm font-bold text-[#0d2a7a]">
                                        New Laundry Request
                                    </p>

                                    <p class="mt-1 text-sm text-slate-600">
                                        ${order.customer_name} submitted
                                        ${order.service_name}.
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Service No. ${order.service_number}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    id="close-customer-notification"
                                    class="text-slate-400 hover:text-slate-700 text-lg"
                                >
                                    ×
                                </button>

                            </div>

                            <a
                                href="{{ route('staff.orders.index') }}"
                                class="inline-flex mt-3 px-3 py-2 rounded-lg
                                    bg-[#4f74d9] text-white text-xs font-bold
                                    hover:bg-[#3f63c8]"
                            >
                                View Customer Service
                            </a>

                        </div>

                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            const closeButton = document.getElementById(
                'close-customer-notification'
            );

            if (closeButton) {
                closeButton.addEventListener('click', function () {
                    notification.remove();
                });
            }

            setTimeout(function () {
                notification.remove();
            }, 7000);
        }

        async function checkCustomerOrders() {

            try {

                const response = await fetch(
                    "{{ route('staff.orders.customer-notifications') }}",
                    {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                const count = Number(data.count || 0);

                // Update sidebar badge.
                if (count > 0) {

                    badge.textContent = count;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');

                } else {

                    badge.textContent = '0';
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');

                }

                // Do not show a popup immediately when the page first loads.
                if (previousCount === null) {
                    previousCount = count;
                    return;
                }

                // Show notification only when a new customer request appears.
                if (count > previousCount && data.latest) {
                    showCustomerNotification(data.latest);
                }

                previousCount = count;

            } catch (error) {
                console.error(
                    'Customer notification check failed:',
                    error
                );
            }
        }

        // First check.
        checkCustomerOrders();

        // Check every 2 seconds.
        setInterval(
            checkCustomerOrders,
            2000
        );

    });
    </script>

</body>

</html>