<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = InventoryItem::query();

        if ($request->filled('search')) {
            $query->where(
                'item_name',
                'like',
                '%' . $request->search . '%'
            );
        }

        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->category
            );
        }

        $items = $query
            ->latest()
            ->get();

        $categories = InventoryItem::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view(
            'admin.inventory.index',
            compact('items', 'categories')
        );
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'in:Detergent,Laundry Powder,Fabric Conditioner,Bleach,Stain Remover,Laundry Soap,Laundry Supply,Other',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
                'lte:max_capacity',
            ],

            'max_capacity' => [
                'required',
                'integer',
                'min:10',
                'max:200',
            ],

            'reorder_level' => [
                'required',
                'integer',
                'min:10',
                'max:200',
                'lt:max_capacity',
            ],

            'unit' => [
                'required',
                'in:Piece,Kilogram (kg),Liter (L),Bottle,Pack,Box',
            ],
        ]);

        InventoryItem::create($validated);

        return redirect()
            ->route('admin.inventory.index')
            ->with(
                'success',
                'Inventory item added successfully.'
            );
    }

    public function edit(InventoryItem $inventoryItem)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view(
            'admin.inventory.edit',
            compact('inventoryItem')
        );
    }

    public function update(
        Request $request,
        InventoryItem $inventoryItem
    ) {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'in:Detergent,Laundry Powder,Fabric Conditioner,Bleach,Stain Remover,Laundry Soap,Laundry Supply,Other',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
                'lte:max_capacity',
            ],

            'max_capacity' => [
                'required',
                'integer',
                'min:10',
                'max:200',
            ],

            'reorder_level' => [
                'required',
                'integer',
                'min:10',
                'max:200',
                'lt:max_capacity',
            ],

            'unit' => [
                'required',
                'in:Piece,Kilogram (kg),Liter (L),Bottle,Pack,Box',
            ],
        ]);

        $inventoryItem->update($validated);

        return redirect()
            ->route('admin.inventory.index')
            ->with(
                'success',
                'Inventory item updated successfully.'
            );
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($inventoryItem->transactions()->exists()) {
            return back()->with(
                'error',
                'This inventory item cannot be deleted because it already has stock transaction records.'
            );
        }

        $inventoryItem->delete();

        return redirect()
            ->route('admin.inventory.index')
            ->with(
                'success',
                'Inventory item removed successfully.'
            );
    }

    public function monitor()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403);
        }

        $items = InventoryItem::orderBy('item_name')->get();

        return view(
            'admin.inventory.monitor',
            compact('items')
        );
    }
    public function view()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403);
        }

        $items = InventoryItem::orderBy('item_name')->get();

        return view(
            'admin.inventory.view',
            compact('items')
        );
    }
}