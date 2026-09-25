<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    private function authorizeAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        $machines = Machine::query()
            ->orderBy('machine_name')
            ->get();

        return view('admin.machines.index', compact('machines'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'machine_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Available,In Use,Maintenance'],
            'maintenance_period' => ['nullable', 'string', 'max:100'],
            'last_maintenance' => ['nullable', 'date'],
            'next_maintenance' => ['nullable', 'date'],
        ]);

        Machine::create($validated);

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine added successfully.');
    }

    public function edit(Machine $machine)
    {
        $this->authorizeAdmin();

        return view('admin.machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'machine_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Available,In Use,Maintenance'],
            'maintenance_period' => ['nullable', 'string', 'max:100'],
            'last_maintenance' => ['nullable', 'date'],
            'next_maintenance' => ['nullable', 'date'],
        ]);

        $machine->update($validated);

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine updated successfully.');
    }

    public function destroy(Machine $machine)
    {
        $this->authorizeAdmin();

        $machine->delete();

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine deleted successfully.');
    }
}