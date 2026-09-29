@extends(auth()->user()->role === 'admin' ? 'admin.layout' : 'staff.layout')

@section('title', 'Machine Monitoring')

@section('content')

<div class="mb-7">

    <p class="text-sm font-semibold text-blue-600">
        Laundry Operations
    </p>

    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">

        <div>

            <h1 class="mt-1 text-3xl font-extrabold text-[#0d2a7a]">
                Machine Monitoring
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Monitor the TOP washing and BOTTOM drying sections of each physical machine.
            </p>

        </div>


        <div class="text-xs text-slate-400">
            Machine status is based on active laundry orders.
        </div>

    </div>

</div>


{{-- SUMMARY --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">


    {{-- Machines --}}
    <div class="rounded-2xl border border-blue-100 bg-white p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
            Physical Machines
        </p>

        <p class="mt-2 text-3xl font-extrabold text-[#0d2a7a]">
            {{ $totalMachines }}
        </p>

        <p class="mt-1 text-xs text-slate-500">
            Machines configured
        </p>

    </div>


    {{-- Washing --}}
    <div class="rounded-2xl border border-blue-100 bg-white p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
            Washing Sections
        </p>

        <p class="mt-2 text-3xl font-extrabold text-blue-600">
            {{ $washingSlotsUsed }} / {{ $totalMachines }}
        </p>

        <p class="mt-1 text-xs text-slate-500">
            {{ $availableWashingSlots }} available TOP section(s)
        </p>

    </div>


    {{-- Drying --}}
    <div class="rounded-2xl border border-indigo-100 bg-white p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-indigo-500">
            Drying Sections
        </p>

        <p class="mt-2 text-3xl font-extrabold text-indigo-600">
            {{ $dryingSlotsUsed }} / {{ $totalMachines }}
        </p>

        <p class="mt-1 text-xs text-slate-500">
            {{ $availableDryingSlots }} available BOTTOM section(s)
        </p>

    </div>


    {{-- Total Available --}}
    <div class="rounded-2xl border border-emerald-100 bg-white p-5">

        <p class="text-xs font-bold uppercase tracking-wide text-emerald-600">
            Available Sections
        </p>

        <p class="mt-2 text-3xl font-extrabold text-emerald-600">
            {{ $availableWashingSlots + $availableDryingSlots }}
        </p>

        <p class="mt-1 text-xs text-slate-500">
            Across both sections
        </p>

    </div>

</div>


{{-- MACHINE CARDS --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    @forelse ($machineSlots as $slot)

        @php

            $machine =
                $slot['machine'];

            $washingOrder =
                $slot['washing'];

            $dryingOrder =
                $slot['drying'];

            $washingAvailable =
                $washingOrder === null;

            $dryingAvailable =
                $dryingOrder === null;

        @endphp


        <section
            class="rounded-2xl border border-blue-100 bg-white overflow-hidden"
        >


            {{-- Machine Header --}}
            <div
                class="px-6 py-5 border-b border-blue-100
                       flex items-center justify-between"
            >

                <div>

                    <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                        Physical Machine
                    </p>

                    <h2 class="mt-1 text-xl font-extrabold text-[#0d2a7a]">
                        {{ $machine->machine_name }}
                    </h2>

                </div>


                @if ($washingAvailable && $dryingAvailable)

                    <span
                        class="inline-flex rounded-full
                               bg-emerald-50 text-emerald-700
                               border border-emerald-200
                               px-3 py-1 text-xs font-bold"
                    >
                        Available
                    </span>

                @else

                    <span
                        class="inline-flex rounded-full
                               bg-amber-50 text-amber-700
                               border border-amber-200
                               px-3 py-1 text-xs font-bold"
                    >
                        In Use
                    </span>

                @endif

            </div>


            {{-- TOP / WASHING --}}
            <div class="p-6 border-b border-slate-100">

                <div class="flex items-center justify-between gap-3 mb-4">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wide text-blue-600">
                            TOP SECTION
                        </p>

                        <p class="mt-1 text-sm font-bold text-slate-700">
                            Washing
                        </p>

                    </div>


                    @if ($washingAvailable)

                        <span
                            class="inline-flex rounded-full
                                   bg-emerald-50 text-emerald-700
                                   border border-emerald-200
                                   px-3 py-1 text-xs font-bold"
                        >
                            Available
                        </span>

                    @else

                        <span
                            class="inline-flex rounded-full
                                   bg-blue-50 text-blue-700
                                   border border-blue-200
                                   px-3 py-1 text-xs font-bold"
                        >
                            Washing
                        </span>

                    @endif

                </div>


                @if ($washingOrder)

                    <div class="rounded-xl bg-blue-50 border border-blue-100 p-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-blue-500">
                            Customer
                        </p>

                        <p class="mt-1 text-base font-extrabold text-[#0d2a7a]">
                            {{ $washingOrder->customer->full_name }}
                        </p>


                        <div class="mt-3 grid grid-cols-2 gap-3">

                            <div>

                                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                    Service No.
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-700">
                                    {{ $washingOrder->service_number }}
                                </p>

                            </div>


                            <div>

                                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                    Service
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-700">
                                    {{ $washingOrder->service->service_name }}
                                </p>

                            </div>

                        </div>


                        @if ($washingOrder->load_count > 0)

                            <p class="mt-3 text-xs font-semibold text-blue-600">
                                {{ $washingOrder->load_count }}
                                {{ $washingOrder->load_count === 1 ? 'load' : 'loads' }}
                                assigned to this laundry.
                            </p>

                        @else

                            <p class="mt-3 text-xs font-semibold text-blue-600">
                                Self Service washing.
                            </p>

                        @endif

                    </div>

                @else

                    <div class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50 p-5 text-center">

                        <p class="text-sm font-bold text-emerald-700">
                            TOP is available
                        </p>

                        <p class="mt-1 text-xs text-emerald-600">
                            A new laundry order can be assigned here.
                        </p>

                    </div>

                @endif

            </div>


            {{-- BOTTOM / DRYING --}}
            <div class="p-6">

                <div class="flex items-center justify-between gap-3 mb-4">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wide text-indigo-600">
                            BOTTOM SECTION
                        </p>

                        <p class="mt-1 text-sm font-bold text-slate-700">
                            Drying
                        </p>

                    </div>


                    @if ($dryingAvailable)

                        <span
                            class="inline-flex rounded-full
                                   bg-emerald-50 text-emerald-700
                                   border border-emerald-200
                                   px-3 py-1 text-xs font-bold"
                        >
                            Available
                        </span>

                    @else

                        <span
                            class="inline-flex rounded-full
                                   bg-indigo-50 text-indigo-700
                                   border border-indigo-200
                                   px-3 py-1 text-xs font-bold"
                        >
                            Drying
                        </span>

                    @endif

                </div>


                @if ($dryingOrder)

                    <div class="rounded-xl bg-indigo-50 border border-indigo-100 p-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-indigo-500">
                            Customer
                        </p>

                        <p class="mt-1 text-base font-extrabold text-[#0d2a7a]">
                            {{ $dryingOrder->customer->full_name }}
                        </p>


                        <div class="mt-3 grid grid-cols-2 gap-3">

                            <div>

                                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                    Service No.
                                </p>

                                <p class="mt-1 text-sm font-bold text-slate-700">
                                    {{ $dryingOrder->service_number }}
                                </p>

                            </div>


                            <div>

                                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                    Service
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-700">
                                    {{ $dryingOrder->service->service_name }}
                                </p>

                            </div>

                        </div>


                        @if ($dryingOrder->load_count > 0)

                            <p class="mt-3 text-xs font-semibold text-indigo-600">
                                {{ $dryingOrder->load_count }}
                                {{ $dryingOrder->load_count === 1 ? 'load' : 'loads' }}
                                drying.
                            </p>

                        @else

                            <p class="mt-3 text-xs font-semibold text-indigo-600">
                                Self Service drying.
                            </p>

                        @endif

                    </div>

                @else

                    <div class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50 p-5 text-center">

                        <p class="text-sm font-bold text-emerald-700">
                            BOTTOM is available
                        </p>

                        <p class="mt-1 text-xs text-emerald-600">
                            A laundry can move into drying here.
                        </p>

                    </div>

                @endif

            </div>

        </section>

    @empty

        <div class="xl:col-span-2 rounded-2xl border border-red-200 bg-red-50 p-8 text-center">

            <p class="font-bold text-red-700">
                No physical machines are configured.
            </p>

            <p class="mt-1 text-sm text-red-600">
                Add the four physical machines before using Machine Monitoring.
            </p>

        </div>

    @endforelse

</div>


{{-- Legend --}}
<div class="mt-6 rounded-2xl border border-blue-100 bg-white p-5">

    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
        How Machine Assignment Works
    </p>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

        <div>

            <p class="font-bold text-blue-700">
                TOP = Washing
            </p>

            <p class="mt-1 text-xs text-slate-500">
                A new laundry can use a TOP section as soon as it becomes available.
            </p>

        </div>


        <div>

            <p class="font-bold text-indigo-700">
                BOTTOM = Drying
            </p>

            <p class="mt-1 text-xs text-slate-500">
                The same physical machine can continue drying while its TOP accepts another laundry.
            </p>

        </div>


        <div>

            <p class="font-bold text-emerald-700">
                Released after Drying
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Once laundry moves from Drying to Folding, its machine sections are released.
            </p>

        </div>

    </div>

</div>

@endsection