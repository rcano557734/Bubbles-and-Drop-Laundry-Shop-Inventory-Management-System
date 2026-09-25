<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Management Sign In</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#eaf3fd]">

    <div class="min-h-screen w-full flex flex-col md:flex-row">

        <!-- LEFT SIDE -->
        <section class="hidden md:flex md:w-1/2 min-h-screen
                        flex-col justify-between
                        bg-gradient-to-br from-[#1a3fc7] via-[#2f5fdc] to-[#5a8ef0]
                        px-10 py-10 lg:px-16 text-white">

            <!-- Brand -->
            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-white/15 ring-1 ring-white/25">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-6 w-6">

                        <path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.72-3.19S7.2 6.13 7 5c-.2 1.13-1.43 2.03-2.28 4.06C3.57 10.05 3 11.14 3 12.3c0 2.22 1.8 4 4 4Z" />

                        <path d="M17.2 15.3c1.5 0 2.8-1.28 2.8-2.83 0-.82-.4-1.58-1.2-2.23-.75-.65-1.4-1.35-1.55-2.24-.14.79-.99 1.42-1.6 2.84-.4.6-.8 1.3-.8 1.63 0 1.55 1.3 2.83 2.8 2.83Z" />

                    </svg>

                </div>

                <span class="text-lg font-bold tracking-tight">
                    Bubbles &amp; Drop
                </span>

            </div>


            <!-- Main Text -->
            <div class="max-w-md">

                <h1 class="text-5xl lg:text-6xl font-extrabold leading-[1.08] tracking-tight">
                    Inventory Management System
                </h1>

                <p class="mt-5 max-w-sm text-base text-blue-100">
                    Track stock levels, log deliveries, and manage supplies — all in one place.
                </p>

            </div>


            <!-- Features -->
            <div class="grid grid-cols-3 gap-4">

                <!-- Track Stock -->
                <div class="rounded-2xl border border-white/15 bg-white/10 p-4">

                    <div class="mb-3 flex h-10 w-10 items-center justify-center
                                rounded-lg bg-white/90 text-[#1a3fc7]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5">

                            <path d="m7.5 4.27 9 5.15" />
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                            <path d="m3.3 7 8.7 5 8.7-5" />
                            <path d="M12 22V12" />

                        </svg>

                    </div>

                    <p class="text-sm font-bold">
                        Track Stock
                    </p>

                </div>


                <!-- Monitor Levels -->
                <div class="rounded-2xl border border-white/15 bg-white/10 p-4">

                    <div class="mb-3 flex h-10 w-10 items-center justify-center
                                rounded-lg bg-white/90 text-[#1a3fc7]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5">

                            <path d="M3 3v18h18" />
                            <path d="M18 17V9" />
                            <path d="M13 17V5" />
                            <path d="M8 17v-3" />

                        </svg>

                    </div>

                    <p class="text-sm font-bold">
                        Monitor Levels
                    </p>

                </div>


                <!-- Log Transactions -->
                <div class="rounded-2xl border border-white/15 bg-white/10 p-4">

                    <div class="mb-3 flex h-10 w-10 items-center justify-center
                                rounded-lg bg-white/90 text-[#1a3fc7]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5">

                            <rect x="8" y="2" width="8" height="4" rx="1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <path d="M9 13h6" />
                            <path d="M9 17h6" />

                        </svg>

                    </div>

                    <p class="text-sm font-bold">
                        Log Transactions
                    </p>

                </div>

            </div>


            <!-- Copyright -->
            <p class="text-xs text-blue-100/80">
                &copy; {{ date('Y') }} Bubbles and Drop Laundry Shop
            </p>

        </section>


        <!-- RIGHT SIDE -->
        <section class="w-full md:w-1/2 min-h-screen
                        flex items-center justify-center
                        bg-gradient-to-br from-[#eaf3fd] to-[#bcdff9]
                        px-6 py-10">

            <div class="w-full max-w-md">

                <!-- Heading -->
                <div class="mb-8">

                    <h2 class="text-4xl font-extrabold tracking-tight text-[#0d2a7a]">
                        Management Sign In
                    </h2>

                    <p class="mt-2 text-lg font-medium text-[#2f6fe0]">
                        Management Portal
                    </p>

                </div>


                <!-- Session Message -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')" />


                <!-- Login Form -->
                <form
                    method="POST"
                    action="{{ route('login') }}"
                    class="space-y-5">

                    @csrf


                    <!-- Email -->
                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#1e4fc4]">

                            Email Address

                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email address"
                            class="w-full rounded-full border-0 bg-white
                                   px-5 py-3.5
                                   text-base text-slate-800
                                   placeholder:text-slate-400
                                   shadow-sm
                                   outline-none
                                   ring-1 ring-slate-200
                                   focus:ring-2 focus:ring-[#2f6fe0]">

                        @error('email')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <!-- Password -->
                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#1e4fc4]">

                            Password

                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="w-full rounded-full border-0 bg-white
                                   px-5 py-3.5
                                   text-base text-slate-800
                                   placeholder:text-slate-400
                                   shadow-sm
                                   outline-none
                                   ring-1 ring-slate-200
                                   focus:ring-2 focus:ring-[#2f6fe0]">

                        @error('password')

                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <!-- Remember / Forgot -->
                    <div class="flex items-center justify-between text-sm text-[#2f6fe0]">

                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 rounded border-blue-300
                                       text-[#2f6fe0]
                                       focus:ring-[#2f6fe0]">

                            <span>
                                Remember me
                            </span>

                        </label>


                        @if (Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="font-medium hover:underline">

                                Forgot password?

                            </a>

                        @endif

                    </div>


                    <!-- Submit -->
                    <button
                        type="submit"
                        class="w-full rounded-full
                               bg-gradient-to-r from-[#0d3fa6] to-[#1a6ee0]
                               px-5 py-3.5
                               text-base font-bold text-white
                               shadow-lg shadow-blue-700/30
                               transition hover:brightness-110">

                        Sign In

                    </button>


                    <p class="pt-1 text-center text-sm text-[#3a72d9]">
                        Use credentials provided by your administrator
                    </p>

                </form>

            </div>

        </section>

    </div>

</body>

</html>