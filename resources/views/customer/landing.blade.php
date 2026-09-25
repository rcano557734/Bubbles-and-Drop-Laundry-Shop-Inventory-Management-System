<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Bubble & Drop Laundry</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-[#d6ecfd]">

    <!-- Header -->
    <header class="relative bg-[#1d3fa6] text-white text-center px-4 pt-8 pb-24 overflow-hidden">

        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                Welcome to Bubble &amp; Drop!
            </h1>

            <p class="mt-2 text-base md:text-lg text-blue-100">
                Choose an option to get started.
            </p>
        </div>

        <!-- Blue Wave -->
        <svg
            class="absolute left-0 bottom-[-1px] w-full h-20 md:h-24"
            viewBox="0 0 1440 100"
            preserveAspectRatio="none"
            aria-hidden="true">

            <path
                d="M0 22
                   C120 8 220 4 320 34
                   C430 66 520 70 640 58
                   C800 42 900 10 1040 14
                   C1180 18 1300 50 1440 52
                   L1440 100
                   L0 100 Z"
                fill="#7fa8d1"
                opacity=".55"
                transform="translate(0 4)" />

            <path
                d="M0 18
                   C120 4 220 0 320 30
                   C430 62 520 66 640 54
                   C800 38 900 6 1040 10
                   C1180 14 1300 46 1440 48
                   L1440 100
                   L0 100 Z"
                fill="#98d0ff" />

            <path
                d="M0 30
                   C120 14 220 12 320 42
                   C430 72 520 76 640 64
                   C800 48 900 18 1040 22
                   C1180 26 1300 56 1440 58
                   L1440 100
                   L0 100 Z"
                fill="#d6ecfd" />
        </svg>

    </header>


    <!-- Main -->
    <main class="flex-1 flex items-center justify-center px-6 py-12">

        <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-20 w-full max-w-5xl">

            <!-- Check My Laundry -->
            <a href="{{ route('customer.check-laundry') }}"
               class="group w-full max-w-[300px] h-[320px]
                      rounded-[22px]
                      bg-[#d9e6fd]
                      border border-[#2a3fd0]
                      flex flex-col items-center justify-center
                      gap-4
                      text-decoration-none
                      transition-all duration-200
                      hover:-translate-y-1
                      hover:shadow-[0_10px_24px_rgba(29,63,166,0.18)]
                      focus:outline-none
                      focus:ring-4
                      focus:ring-blue-300/50">

                <!-- Icon Tile -->
                <div class="w-[135px] h-[135px]
                            rounded-2xl
                            bg-[#96c2fc]
                            flex items-center justify-center
                            text-[#1420ff]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 96 96"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="3"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-24 h-24">

                        <circle cx="42" cy="42" r="23" />
                        <line x1="59" y1="59" x2="79" y2="79" />

                        <path d="M34 42h16" />
                        <path d="M42 34v16" />

                    </svg>

                </div>

                <h2 class="text-2xl font-bold text-[#1d3a8f]">
                    Check My Laundry
                </h2>

                <p class="text-sm text-slate-600 text-center px-8">
                    Check the current status of your laundry order.
                </p>

            </a>


            <!-- Avail Service -->
            <a href="{{ route('customer.avail-service') }}"
               class="group w-full max-w-[300px] h-[320px]
                      rounded-[22px]
                      bg-[#fddde2]
                      border border-[#f08c98]
                      flex flex-col items-center justify-center
                      gap-4
                      text-decoration-none
                      transition-all duration-200
                      hover:-translate-y-1
                      hover:shadow-[0_10px_24px_rgba(180,20,28,0.16)]
                      focus:outline-none
                      focus:ring-4
                      focus:ring-red-200/70">

                <!-- Icon Tile -->
                <div class="w-[135px] h-[135px]
                            rounded-2xl
                            bg-[#ffb4b4]
                            flex items-center justify-center
                            text-[#c4361f]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 96 96"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="3"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-24 h-24">

                        <path d="M20 28h56" />
                        <path d="M24 28v40a6 6 0 0 0 6 6h36a6 6 0 0 0 6-6V28" />
                        <path d="M32 28l6-10h20l6 10" />

                        <circle cx="48" cy="49" r="15" />
                        <path d="M40 49h16" />

                    </svg>

                </div>

                <h2 class="text-2xl font-bold text-[#b3141c]">
                    Avail Service
                </h2>

                <p class="text-sm text-slate-600 text-center px-8">
                    Start a new laundry service with us.
                </p>

            </a>

        </div>

    </main>


    <!-- Footer -->
    <footer class="bg-[#1d3fa6] text-white px-6 py-4">

        <div class="max-w-7xl mx-auto flex justify-end">

            <div class="flex items-center gap-3 text-right">

                <!-- Location Icon -->
                <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="w-5 h-5">

                        <path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z" />
                        <circle cx="12" cy="9.5" r="2.5" />

                    </svg>

                </div>

                <div>
                    <p class="font-semibold text-sm">
                        Matina Aplaya Road
                    </p>

                    <p class="text-xs text-blue-200">
                        Davao City, Davao Region
                    </p>
                </div>

            </div>

        </div>

    </footer>

</body>

</html>