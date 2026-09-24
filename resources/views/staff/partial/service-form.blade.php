@php
    $formService = $service ?? [];
    $formPrice = isset($formService['price'])
        ? preg_replace('/[^0-9.]/', '', $formService['price'])
        : '';
    $statuses = ['Waiting', 'Washing', 'Drying', 'Ready for Pickup', 'Completed'];
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="customer" class="mb-1 block text-xs font-semibold text-blue-700">Customer name *</label>
        <input id="customer" name="customer" required value="{{ old('customer', $formService['customer'] ?? '') }}"
            class="w-full rounded-lg border border-blue-200 px-3 py-2 text-sm text-slate-800" />
    </div>
    <div>
        <label for="service_type" class="mb-1 block text-xs font-semibold text-blue-700">Service type *</label>
        <select id="service_type" name="service_type" required class="w-full rounded-lg border border-blue-200 px-3 py-2 text-sm text-slate-800">
            @foreach(['Self-Service', 'Wash, Dry, Fold', 'Wash and Fold', 'Dry Only'] as $type)
            <option value="{{ $type }}" @selected(old('service_type', $formService['service_type'] ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="price" class="mb-1 block text-xs font-semibold text-blue-700">Price (PHP) *</label>
        <input id="price" name="price" type="number" min="0" step="0.01" required value="{{ old('price', $formPrice) }}"
            class="w-full rounded-lg border border-blue-200 px-3 py-2 text-sm text-slate-800" />
    </div>
    <div>
        <label for="kilos_loads" class="mb-1 block text-xs font-semibold text-blue-700">Kilos / loads *</label>
        <input id="kilos_loads" name="kilos_loads" required placeholder="e.g. 5kg | 1 load"
            value="{{ old('kilos_loads', $formService['kilos_loads'] ?? '') }}"
            class="w-full rounded-lg border border-blue-200 px-3 py-2 text-sm text-slate-800" />
    </div>
    <div>
        <label for="detergent" class="mb-1 block text-xs font-semibold text-blue-700">Detergent</label>
        <input id="detergent" name="detergent" value="{{ old('detergent', $formService['detergent'] ?? '') }}"
            class="w-full rounded-lg border border-blue-200 px-3 py-2 text-sm text-slate-800" />
    </div>
    <div>
        <label for="machine" class="mb-1 block text-xs font-semibold text-blue-700">Machine</label>
        <input id="machine" name="machine" value="{{ old('machine', $formService['machine'] ?? '') }}"
            class="w-full rounded-lg border border-blue-200 px-3 py-2 text-sm text-slate-800" />
    </div>
    <div class="sm:col-span-2">
        <label for="status" class="mb-1 block text-xs font-semibold text-blue-700">Status *</label>
        <select id="status" name="status" required class="w-full rounded-lg border border-blue-200 px-3 py-2 text-sm text-slate-800">
            @foreach($statuses as $status)
            <option value="{{ $status }}" @selected(old('status', $formService['status'] ?? 'Waiting') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>
