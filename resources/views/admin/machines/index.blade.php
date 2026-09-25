@extends('admin.layout')

@section('title', 'Machine Management')

@section('content')

    {{-- Header --}}
    <div class="mb-7">

        <h1 class="text-3xl font-bold text-[#183984]">
            Machine Management
        </h1>

        <p class="text-sm text-blue-500 mt-1">
            Add, update, and manage laundry machines.
        </p>

    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-5 rounded-xl bg-green-50 border border-green-100 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-100 px-5 py-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add Machine --}}
    <section class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-6">

        <h2 class="text-lg font-semibold text-blue-800">
            Add Machine
        </h2>

        <p class="text-sm text-gray-500 mt-1 mb-5">
            Register a new machine in the laundry shop.
        </p>

        <form method="POST" action="{{ route('admin.machines.store') }}">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-blue-600 mb-2">
                        Machine Name
                    </label>

                    <input
                        type="text"
                        name="machine_name"
                        value="{{ old('machine_name') }}"
                        placeholder="e.g. Washer 6"
                        required
                        class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-blue-600 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >
                        <option value="Available">Available</option>
                        <option value="In Use">In Use</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-blue-600 mb-2">
                        Maintenance Period
                    </label>

                    <input
                        type="text"
                        name="maintenance_period"
                        value="{{ old('maintenance_period') }}"
                        placeholder="e.g. Every 3 months"
                        class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >
                </div>

                <div class="flex items-end">

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-[#4f74d9] px-5 py-3 text-sm font-bold text-white hover:bg-[#3f63c8] transition"
                    >
                        + Add Machine
                    </button>

                </div>

            </div>

        </form>

    </section>

    {{-- Machine List --}}
    <section class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">

            <h2 class="font-semibold text-blue-800">
                Machines
            </h2>

            <p class="text-xs text-gray-500 mt-1">
                {{ $machines->count() }} machine(s)
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px]">

                <thead class="bg-[#eff6ff]">

                    <tr class="text-left">

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Machine
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Status
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Maintenance
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Last Maintenance
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Next Maintenance
                        </th>

                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-blue-700">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-blue-50">

                    @forelse ($machines as $machine)

                        @php
                            $statusClass = match ($machine->status) {
                                'Available' => 'bg-green-50 text-green-700 border-green-200',
                                'In Use' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        @endphp

                        <tr class="hover:bg-blue-50/40 transition">

                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $machine->machine_name }}
                            </td>

                            <td class="px-5 py-4">

                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold {{ $statusClass }}">
                                    {{ $machine->status }}
                                </span>

                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $machine->maintenance_period ?? '—' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $machine->last_maintenance?->format('M d, Y') ?? '—' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $machine->next_maintenance?->format('M d, Y') ?? '—' }}
                            </td>

                            <td class="px-5 py-4">

                                <div class="flex gap-2">

                                    <a
                                        href="{{ route('admin.machines.edit', $machine) }}"
                                        class="rounded-lg bg-[#4f74d9] px-4 py-2 text-xs font-bold text-white hover:bg-[#3f63c8]"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.machines.destroy', $machine) }}"
                                        onsubmit="return confirm('Delete this machine?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-50 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-100"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-5 py-12 text-center">

                                <p class="font-semibold text-slate-700">
                                    No machines added yet.
                                </p>

                                <p class="text-sm text-slate-400 mt-1">
                                    Use the form above to add your first machine.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

@endsection