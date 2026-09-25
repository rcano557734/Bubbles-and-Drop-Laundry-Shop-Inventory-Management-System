<?php

namespace App\Http\Controllers;

class StaffController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'staff') {
            abort(403);
        }

        $totalItems = \App\Models\InventoryItem::count();

        $lowStockItems = \App\Models\InventoryItem::query()
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->whereColumn('quantity', '<', 'max_capacity')
            ->orderBy('item_name')
            ->get();

        $lowStockCount = $lowStockItems->count();

        $today = now()->toDateString();

        $stockInToday = \App\Models\InventoryTransaction::query()
            ->where('type', 'stock-in')
            ->whereDate('date', $today)
            ->count();

        $stockOutToday = \App\Models\InventoryTransaction::query()
            ->where('type', 'stock-out')
            ->whereDate('date', $today)
            ->count();

        $activeOrders = \App\Models\LaundryOrder::query()
            ->where('status', '!=', 'Claimed')
            ->count();

        $receivedOrders = \App\Models\LaundryOrder::query()
            ->where('status', 'Received')
            ->count();

        $washingOrders = \App\Models\LaundryOrder::query()
            ->where('status', 'Washing')
            ->count();

        $readyOrders = \App\Models\LaundryOrder::query()
            ->where('status', 'Ready for Pickup')
            ->count();

        $recentStockIns = \App\Models\InventoryTransaction::query()
            ->with('inventoryItem')
            ->where('type', 'stock-in')
            ->latest('date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'totalItems',
            'lowStockItems',
            'lowStockCount',
            'stockInToday',
            'stockOutToday',
            'activeOrders',
            'receivedOrders',
            'washingOrders',
            'readyOrders',
            'recentStockIns'
        ));
    }
}