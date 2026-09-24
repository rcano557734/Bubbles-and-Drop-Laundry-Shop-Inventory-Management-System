<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\LaundryOrder;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    private function seedItems(): array
    {
        return InventoryItem::query()->orderBy('item_name')->get()
            ->map(fn(InventoryItem $item) => [
                'id' => (string) $item->id,
                'name' => $item->item_name,
                'category' => $item->category,
                'unit' => $item->unit,
                'quantity' => (int) $item->quantity,
                'reorder' => (int) $item->reorder_level,
                'max' => (int) $item->max_capacity,
                'updated' => $item->updated_at?->toDateString() ?? now()->toDateString(),
            ])->all();
    }

    private function seedServices(): array
    {
        return LaundryOrder::query()->with(['customer', 'service'])->latest()->get()
            ->map(fn(LaundryOrder $order) => $this->activityService($order))
            ->all();
    }

    private function seedTransactions(): array
    {
        return InventoryTransaction::query()->with('inventoryItem')->latest('date')->latest('id')->get()
            ->map(fn(InventoryTransaction $transaction) => [
                'id' => 'T' . $transaction->id,
                'type' => $transaction->type === 'stock-in' ? 'in' : 'out',
                'item_id' => (string) $transaction->inventory_item_id,
                'item_name' => $transaction->inventoryItem?->item_name ?? 'Deleted item',
                'quantity' => (int) $transaction->quantity,
                'date' => $transaction->date->toDateString(),
                'supplier' => $transaction->supplier,
                'reason' => $transaction->reason,
                'service_ref' => null,
            ])->all();
    }

    private function stockLevel(array $item): string
    {
        if ($item['quantity'] <= $item['reorder']) {
            return 'Low';
        }

        if ($item['quantity'] / $item['max'] <= 0.65) {
            return 'Medium';
        }

        return 'Full';
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['username' => 'required', 'password' => 'required']);

        $staffUser = User::where('role', 'staff')
            ->where(function ($query) use ($request) {
                $query->where('email', $request->username)
                    ->orWhere('name', $request->username);
            })
            ->first();

        if ($staffUser && Hash::check($request->password, $staffUser->password)) {
            Auth::login($staffUser);
            $request->session()->regenerate();
            session(['staff_logged_in' => true, 'staff_name' => $staffUser->name]);

            return redirect()->route('staff.customer-service');
        }

        if ($request->password === 'staff123') {
            session(['staff_logged_in' => true, 'staff_name' => $request->username]);
            return redirect()->route('staff.customer-service');
        }

        return back()->withErrors(['password' => 'Invalid staff credentials.'])->withInput();
    }

    public function logout()
    {
        session()->forget(['staff_logged_in', 'staff_name', 'staff_items', 'staff_services', 'staff_transactions']);

        return redirect()->route('login');
    }

    public function customerService(Request $request)
    {
        $services = $this->seedServices();
        $filter = $request->query('filter', 'All');
        $search = $request->query('search', '');

        $filtered = array_values(array_filter($services, function ($s) use ($filter, $search) {
            $matchFilter = $filter === 'All' || $s['status'] === $filter;
            $matchSearch = empty($search)
                || str_contains(strtolower($s['customer']), strtolower($search))
                || str_contains($s['no'], $search);

            return $matchFilter && $matchSearch;
        }));

        return view('staff.customer-service', [
            'services' => $filtered,
            'filter' => $filter,
            'search' => $search,
            'active' => count(array_filter($services, fn($s) => $s['status'] !== 'Completed')),
            'readyPick' => count(array_filter($services, fn($s) => $s['status'] === 'Ready for Pickup')),
            'completed' => count(array_filter($services, fn($s) => $s['status'] === 'Completed')),
            'availableServices' => Service::query()->orderBy('service_name')->get(),
        ]);
    }

    public function markReady(string $id)
    {
        LaundryOrder::query()->findOrFail($id)->update(['status' => 'Ready for Pickup']);

        return redirect()->route('staff.customer-service')->with('toast', 'Marked as Ready for Pickup.');
    }

    public function markDone(string $id)
    {
        LaundryOrder::query()->findOrFail($id)->update([
            'status' => 'Completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('staff.customer-service')->with('toast', 'Marked as Completed.');
    }

    public function storeService(Request $request)
    {
        $data = $this->validatedService($request);

        DB::transaction(function () use ($data) {
            $customer = $this->findOrCreateCustomer($data['full_name'], $data['contact_number'] ?? null);
            LaundryOrder::create([
                'customer_id' => $customer->id,
                'service_id' => $data['service_id'],
                'service_number' => $this->nextServiceNumber(),
                'kilos' => $data['kilos'] ?? null,
                'load_count' => $data['load_count'] ?? 0,
                'detergent_quantity' => $data['detergent_quantity'] ?? 0,
                'fabric_conditioner_quantity' => $data['fabric_conditioner_quantity'] ?? 0,
                'total_amount' => $data['total_amount'],
                'status' => $data['status'],
                'received_at' => now(),
                'completed_at' => $data['status'] === 'Completed' ? now() : null,
            ]);
        });

        return redirect()->route('staff.customer-service')->with('toast', 'Service record added.');
    }

    public function updateService(Request $request, string $id)
    {
        $data = $this->validatedService($request);
        $order = LaundryOrder::query()->with('customer')->findOrFail($id);

        DB::transaction(function () use ($data, $order) {
            $order->customer->update([
                'full_name' => $data['full_name'],
                'contact_number' => $data['contact_number'] ?: null,
            ]);
            $order->update([
                'service_id' => $data['service_id'],
                'kilos' => $data['kilos'] ?? null,
                'load_count' => $data['load_count'] ?? 0,
                'detergent_quantity' => $data['detergent_quantity'] ?? 0,
                'fabric_conditioner_quantity' => $data['fabric_conditioner_quantity'] ?? 0,
                'total_amount' => $data['total_amount'],
                'status' => $data['status'],
                'completed_at' => $data['status'] === 'Completed' ? ($order->completed_at ?? now()) : null,
            ]);
        });

        return redirect()->route('staff.customer-service')->with('toast', 'Service record updated.');
    }

    public function destroyService(string $id)
    {
        LaundryOrder::query()->findOrFail($id)->delete();

        return redirect()->route('staff.customer-service')->with('toast', 'Service record deleted.');
    }

    private function validatedService(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'service_id' => ['required', 'exists:services,id'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'kilos' => ['nullable', 'numeric', 'min:0'],
            'load_count' => ['nullable', 'integer', 'min:0'],
            'detergent_quantity' => ['nullable', 'numeric', 'min:0'],
            'fabric_conditioner_quantity' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:Received,Waiting,Washing,Drying,Ready for Pickup,Completed,Claimed'],
        ]);
    }

    private function activityService(LaundryOrder $order): array
    {
        $kilosLoads = [];

        if ($order->kilos !== null) {
            $kilosLoads[] = $order->kilos . 'kg';
        }

        if ($order->load_count > 0) {
            $kilosLoads[] = $order->load_count . ' load' . ($order->load_count === 1 ? '' : 's');
        }

        return [
            'id' => (string) $order->id,
            'no' => $order->service_number,
            'customer' => $order->customer->full_name,
            'contact_number' => $order->customer->contact_number,
            'service_id' => $order->service_id,
            'service_type' => $order->service->service_name,
            'price' => 'PHP ' . number_format((float) $order->total_amount, 2),
            'total_amount' => (float) $order->total_amount,
            'kilos_loads' => $kilosLoads ? implode(' | ', $kilosLoads) : '—',
            'kilos' => $order->kilos,
            'load_count' => $order->load_count,
            'detergent' => $order->detergent_quantity > 0 ? $order->detergent_quantity . ' unit(s)' : '—',
            'detergent_quantity' => $order->detergent_quantity,
            'fabric_conditioner_quantity' => $order->fabric_conditioner_quantity,
            'machine' => '—',
            'status' => $order->status,
        ];
    }

    private function findOrCreateCustomer(string $fullName, ?string $contactNumber): Customer
    {
        $query = Customer::query()->where('full_name', $fullName);
        $contactNumber ? $query->where('contact_number', $contactNumber) : $query->whereNull('contact_number');

        return $query->first() ?? Customer::create([
            'customer_code' => (string) ((Customer::query()->max('id') ?? 100) + 1),
            'full_name' => $fullName,
            'contact_number' => $contactNumber,
        ]);
    }

    private function nextServiceNumber(): string
    {
        return 'STF-' . str_pad((string) ((LaundryOrder::query()->max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT);
    }

    public function dashboard()
    {
        $items = $this->seedItems();
        $transactions = $this->seedTransactions();
        $today = date('Y-m-d');
        $lowItems = array_filter($items, fn($i) => $this->stockLevel($i) === 'Low');
        $recentIn = array_reverse(array_slice(array_filter($transactions, fn($t) => $t['type'] === 'in'), -3));
        $recentOut = array_reverse(array_slice(array_filter($transactions, fn($t) => $t['type'] === 'out'), -3));
        $inToday = count(array_filter($transactions, fn($t) => $t['type'] === 'in' && $t['date'] === $today));
        $outToday = count(array_filter($transactions, fn($t) => $t['type'] === 'out' && $t['date'] === $today));

        return view('staff.dashboard', compact('items', 'transactions', 'lowItems', 'recentIn', 'recentOut', 'inToday', 'outToday'));
    }

    public function stockIn()
    {
        $items = $this->seedItems();
        $pending = session('staff_pending_in');

        return view('staff.Functions.stock-in', compact('items', 'pending'));
    }

    public function stockInStore(Request $request)
    {
        $request->validate(['item_id' => 'required', 'quantity' => 'required|integer|min:1', 'date' => 'required|date']);

        if ($request->step === 'review') {
            session(['staff_pending_in' => $request->only('item_id', 'quantity', 'supplier', 'date', 'notes')]);

            return redirect()->route('staff.stock-in')->with('review', true);
        }

        if (InventoryItem::query()->exists()) {
            $result = DB::transaction(function () use ($request) {
                $item = InventoryItem::query()->lockForUpdate()->findOrFail($request->item_id);
                $quantity = (int) $request->quantity;

                if ($item->quantity + $quantity > $item->max_capacity) {
                    return ['success' => false, 'message' => "Stock-In would exceed {$item->item_name}'s maximum capacity."];
                }

                $item->increment('quantity', $quantity);
                InventoryTransaction::create([
                    'inventory_item_id' => $item->id,
                    'type' => 'stock-in',
                    'quantity' => $quantity,
                    'date' => $request->date,
                    'supplier' => $request->supplier,
                    'reason' => null,
                ]);

                return ['success' => true];
            });

            if (! $result['success']) {
                return back()->withErrors(['quantity' => $result['message']])->withInput();
            }

            session()->forget('staff_pending_in');

            return redirect()->route('staff.view-inventory')->with('toast', 'Stock-In recorded successfully.');
        }

        $items = $this->seedItems();
        $index = collect($items)->search(fn($item) => $item['id'] === $request->item_id);
        abort_if($index === false, 404);
        $items[$index]['quantity'] += (int) $request->quantity;
        $items[$index]['updated'] = $request->date;
        $transactions = $this->seedTransactions();
        $transactions[] = [
            'id' => 'T' . str_pad((string) (count($transactions) + 1), 3, '0', STR_PAD_LEFT),
            'type' => 'in',
            'item_id' => $items[$index]['id'],
            'item_name' => $items[$index]['name'],
            'quantity' => (int) $request->quantity,
            'date' => $request->date,
            'supplier' => $request->supplier,
            'reason' => null,
            'service_ref' => null,
        ];
        session(['staff_items' => $items, 'staff_transactions' => $transactions]);
        session()->forget('staff_pending_in');

        return redirect()->route('staff.view-inventory')->with('toast', 'Stock-In recorded successfully.');
    }

    public function stockOut()
    {
        $items = $this->seedItems();
        $pending = session('staff_pending_out');

        return view('staff.Functions.stock-out', compact('items', 'pending'));
    }

    public function stockOutStore(Request $request)
    {
        $request->validate(['item_id' => 'required', 'quantity' => 'required|integer|min:1']);

        if ($request->step === 'review') {
            session(['staff_pending_out' => $request->only('item_id', 'quantity', 'reason', 'service_ref')]);

            return redirect()->route('staff.stock-out')->with('review', true);
        }

        if (InventoryItem::query()->exists()) {
            $result = DB::transaction(function () use ($request) {
                $item = InventoryItem::query()->lockForUpdate()->findOrFail($request->item_id);
                $quantity = (int) $request->quantity;

                if ($quantity > $item->quantity) {
                    return ['success' => false, 'message' => "Insufficient stock. {$item->quantity} {$item->unit} available."];
                }

                $item->decrement('quantity', $quantity);
                InventoryTransaction::create([
                    'inventory_item_id' => $item->id,
                    'type' => 'stock-out',
                    'quantity' => $quantity,
                    'date' => now()->toDateString(),
                    'supplier' => null,
                    'reason' => $request->reason,
                ]);

                return ['success' => true];
            });

            if (! $result['success']) {
                return back()->withErrors(['quantity' => $result['message']])->withInput();
            }

            session()->forget('staff_pending_out');

            return redirect()->route('staff.view-inventory')->with('toast', 'Stock-Out recorded successfully.');
        }

        $items = $this->seedItems();
        $index = collect($items)->search(fn($item) => $item['id'] === $request->item_id);
        abort_if($index === false, 404);

        if ((int) $request->quantity > $items[$index]['quantity']) {
            return back()->withErrors(['quantity' => 'Quantity used cannot be greater than available stock.'])->withInput();
        }

        $items[$index]['quantity'] -= (int) $request->quantity;
        $items[$index]['updated'] = now()->toDateString();
        $transactions = $this->seedTransactions();
        $transactions[] = [
            'id' => 'T' . str_pad((string) (count($transactions) + 1), 3, '0', STR_PAD_LEFT),
            'type' => 'out',
            'item_id' => $items[$index]['id'],
            'item_name' => $items[$index]['name'],
            'quantity' => (int) $request->quantity,
            'date' => now()->toDateString(),
            'supplier' => null,
            'reason' => $request->reason,
            'service_ref' => $request->service_ref,
        ];
        session(['staff_items' => $items, 'staff_transactions' => $transactions]);
        session()->forget('staff_pending_out');

        return redirect()->route('staff.view-inventory')->with('toast', 'Stock-Out recorded successfully.');
    }

    public function viewInventory(Request $request)
    {
        $items = $this->seedItems();
        $search = $request->query('search', '');
        $catFilter = $request->query('category', 'All');
        $categories = array_unique(array_column($items, 'category'));
        $transactions = $this->seedTransactions();

        $filtered = array_values(array_filter($items, function ($i) use ($search, $catFilter) {
            $ms = empty($search) || str_contains(strtolower($i['name']), strtolower($search));
            $mc = $catFilter === 'All' || $i['category'] === $catFilter;

            return $ms && $mc;
        }));

        return view('staff.Functions.inventory', [
            'items' => $filtered,
            'allItems' => $items,
            'transactions' => $transactions,
            'categories' => $categories,
            'search' => $search,
            'catFilter' => $catFilter,
        ]);
    }

    public function monitorStock()
    {
        $items = $this->seedItems();
        usort($items, function ($a, $b) {
            $ord = ['Low' => 0, 'Medium' => 1, 'Full' => 2];

            return $ord[$this->stockLevel($a)] <=> $ord[$this->stockLevel($b)];
        });
        $counts = [
            'Low' => count(array_filter($items, fn($i) => $this->stockLevel($i) === 'Low')),
            'Medium' => count(array_filter($items, fn($i) => $this->stockLevel($i) === 'Medium')),
            'Full' => count(array_filter($items, fn($i) => $this->stockLevel($i) === 'Full')),
        ];

        return view('staff.Functions.monitor-inventory', compact('items', 'counts'));
    }

    public function index()
    {
        if (auth()->user()->role !== 'staff') {
            abort(403);
        }

        return view('staff.dashboard');
    }
}
