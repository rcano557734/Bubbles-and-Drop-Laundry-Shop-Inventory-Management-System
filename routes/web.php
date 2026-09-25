<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffOrderController;
use App\Http\Controllers\MachineController;
use Illuminate\Support\Facades\Route;


// ============================================================
// Dashboard
// ============================================================

Route::get('/dashboard', function () {

    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (auth()->user()->role === 'staff') {
        return redirect()->route('staff.dashboard');
    }

    abort(403);

})->middleware('auth')->name('dashboard');


// ============================================================
// Customer
// ============================================================

Route::get('/', function () {
    return view('customer.landing');
})->name('customer.landing');

Route::get('/check-laundry', [CustomerController::class, 'showCheckForm'])
    ->name('customer.check-laundry');

Route::post('/check-laundry', [CustomerController::class, 'searchLaundry'])
    ->name('customer.search-laundry');

Route::get('/avail-service', [CustomerController::class, 'showAvailService'])
    ->name('customer.avail-service');

Route::post('/avail-service', [CustomerController::class, 'createOrder'])
    ->name('customer.create-order');


// ============================================================
// Authenticated Users
// ============================================================

Route::middleware('auth')->group(function () {

    // --------------------------------------------------------
    // Admin Dashboard
    // --------------------------------------------------------

    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');


    // --------------------------------------------------------
    // Staff Dashboard
    // --------------------------------------------------------

    Route::get('/staff/dashboard', [StaffController::class, 'index'])
        ->middleware('role:staff')
        ->name('staff.dashboard');


    // --------------------------------------------------------
    // Profile
    // --------------------------------------------------------

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // ========================================================
    // Staff / Shared Operations
    // ========================================================

    // Customer Service Activity
    Route::get('/staff/orders', [StaffOrderController::class, 'index'])
        ->middleware('role:admin,staff')
        ->name('staff.orders.index');

    Route::get('/staff/orders/create', [StaffOrderController::class, 'create'])
        ->middleware('role:admin,staff')
        ->name('staff.orders.create');

    Route::post('/staff/orders', [StaffOrderController::class, 'store'])
        ->middleware('role:admin,staff')
        ->name('staff.orders.store');

    Route::get('/staff/orders/{order}/edit', [StaffOrderController::class, 'edit'])
        ->middleware('role:admin,staff')
        ->name('staff.orders.edit');

    Route::put('/staff/orders/{order}', [StaffOrderController::class, 'update'])
        ->middleware('role:admin,staff')
        ->name('staff.orders.update');


    // --------------------------------------------------------
    // Staff Inventory - View
    // --------------------------------------------------------

    Route::get('/staff/inventory/view', [InventoryController::class, 'view'])
        ->middleware('role:admin,staff')
        ->name('staff.inventory.view');


    // Staff Inventory - Monitor
    Route::get('/staff/inventory/monitor', [InventoryController::class, 'monitor'])
        ->middleware('role:admin,staff')
        ->name('staff.inventory.monitor');


    // Staff Inventory - Stock-In
    Route::get('/staff/inventory/stock-in', [InventoryTransactionController::class, 'create'])
        ->middleware('role:admin,staff')
        ->name('staff.inventory.stock-in');

    Route::post('/staff/inventory/stock-in', [InventoryTransactionController::class, 'storeStockIn'])
        ->middleware('role:admin,staff')
        ->name('staff.inventory.stock-in.store');


    // Staff Inventory - Stock-Out
    Route::get('/staff/inventory/stock-out', [InventoryTransactionController::class, 'createStockOut'])
        ->middleware('role:admin,staff')
        ->name('staff.inventory.stock-out');

    Route::post('/staff/inventory/stock-out', [InventoryTransactionController::class, 'storeStockOut'])
        ->middleware('role:admin,staff')
        ->name('staff.inventory.stock-out.store');


    // ========================================================
    // Admin Inventory Management
    // ========================================================

    Route::get('/admin/inventory', [InventoryController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.inventory.index');

    Route::post('/admin/inventory', [InventoryController::class, 'store'])
        ->middleware('role:admin')
        ->name('admin.inventory.store');

    Route::get('/admin/inventory/{inventoryItem}/edit', [InventoryController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.inventory.edit');

    Route::put('/admin/inventory/{inventoryItem}', [InventoryController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.inventory.update');

    Route::delete('/admin/inventory/{inventoryItem}', [InventoryController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('admin.inventory.destroy');


    // --------------------------------------------------------
    // Admin Inventory View
    // --------------------------------------------------------

    Route::get('/admin/inventory/view', [InventoryController::class, 'view'])
        ->middleware('role:admin')
        ->name('admin.inventory.view');


    // --------------------------------------------------------
    // Admin Inventory Monitor
    // --------------------------------------------------------

    Route::get('/admin/inventory/monitor', [InventoryController::class, 'monitor'])
        ->middleware('role:admin')
        ->name('admin.inventory.monitor');


    // --------------------------------------------------------
    // Admin Inventory Stock-In
    // --------------------------------------------------------

    Route::get('/admin/inventory/stock-in', [InventoryTransactionController::class, 'create'])
        ->middleware('role:admin')
        ->name('admin.inventory.stock-in');

    Route::post('/admin/inventory/stock-in', [InventoryTransactionController::class, 'storeStockIn'])
        ->middleware('role:admin')
        ->name('admin.inventory.stock-in.store');


    // --------------------------------------------------------
    // Admin Inventory Stock-Out
    // --------------------------------------------------------

    Route::get('/admin/inventory/stock-out', [InventoryTransactionController::class, 'createStockOut'])
        ->middleware('role:admin')
        ->name('admin.inventory.stock-out');

    Route::post('/admin/inventory/stock-out', [InventoryTransactionController::class, 'storeStockOut'])
        ->middleware('role:admin')
        ->name('admin.inventory.stock-out.store');

    // ========================================================
    // Admin Machine Management
    // ========================================================

    Route::get('/admin/machines', [MachineController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.machines.index');

    Route::post('/admin/machines', [MachineController::class, 'store'])
        ->middleware('role:admin')
        ->name('admin.machines.store');

    Route::get('/admin/machines/{machine}/edit', [MachineController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.machines.edit');

    Route::put('/admin/machines/{machine}', [MachineController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.machines.update');

    Route::delete('/admin/machines/{machine}', [MachineController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('admin.machines.destroy');    

    });


// ============================================================
// Breeze Authentication
// ============================================================

require __DIR__.'/auth.php';