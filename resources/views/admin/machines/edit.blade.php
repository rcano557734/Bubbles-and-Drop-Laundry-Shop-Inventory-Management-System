@extends('admin.layout')

@section('title', 'Edit Machine')

@section('content')

    <div class="mb-7">

        <h1 class="text-3xl font-bold text-[#183984]">
            Edit Machine
        </h1>

        <p class="text-sm text-blue-500 mt-1">
            Update machine information and maintenance details.
        </p>

    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-100 px-5 py-4 text-sm text-red-700">

            <ul class="list-disc pl-5 space-y-1">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif

    <section class="max-w-3xl bg-white rounded-2xl border border-blue-100 shadow-sm p-6">

        <form
            method="POST"
            action="{{ route('admin.machines.update', $machine) }}"
        >

            @csrf
            @method('PUT')

            <div class="space-y-5">

                <div>

                    <label class="block text-xs font-bold uppercase tracking-wide text-blue-600 mb-2">
                        Machine Name
                    </label>

                    <input
                        type="text"
                        name="machine_name"
                        value="{{ old('machine_name', $machine->machine_name) }}"
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

                        @foreach (['Available', 'In Use', 'Maintenance'] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(old('status', $machine->status) === $status)
                            >
                                {{ $status }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block text-xs font-bold uppercase tracking-wide text-blue-600 mb-2">
                        Maintenance Period
                    </label>

                    <input
                        type="text"
                        name="maintenance_period"
                        value="{{ old('maintenance_period', $machine->maintenance_period) }}"
                        placeholder="e.g. Every 3 months"
                        class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>

                        <label class="block text-xs font-bold uppercase tracking-wide text-blue-600 mb-2">
                            Last Maintenance
                        </label>

                        <input
                            type="date"
                            name="last_maintenance"
                            value="{{ old('last_maintenance', $machine->last_maintenance?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        >

                    </div>

                    <div>

                        <label class="block text-xs font-bold uppercase tracking-wide text-blue-600 mb-2">
                            Next Maintenance
                        </label>

                        <input
                            type="date"
                            name="next_maintenance"
                            value="{{ old('next_maintenance', $machine->next_maintenance?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border border-blue-100 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        >

                    </div>

                </div>

            </div>

            <div class="flex gap-3 mt-7">

                <button
                    type="submit"
                    class="rounded-xl bg-[#4f74d9] px-6 py-3 text-sm font-bold text-white hover:bg-[#3f63c8]"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('admin.machines.index') }}"
                    class="rounded-xl border border-blue-100 px-6 py-3 text-sm font-bold text-slate-600 hover:bg-blue-50"
                >
                    Cancel
                </a>

            </div>

        </form>

    </section>

@endsection