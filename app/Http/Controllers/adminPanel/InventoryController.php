<?php

namespace App\Http\Controllers\adminPanel;

use App\Models\Branch;
use App\Models\AuditLog;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class InventoryController extends Controller
{

    public function inventory(Request $request)
    {
        // Get analytics data
        $totalItems = Inventory::count();
        $totalValue = Inventory::sum(DB::raw('quantity * cost_per_item'));
        $outOfStockCount = Inventory::where('availability', 'out-of-stock')->count();

        // Get low stock items
        $lowStockItems = Inventory::whereRaw('quantity <= minimum_quantity')
            ->where('quantity', '>', 0)
            ->get();
        $lowStockCount = $lowStockItems->count();

        // Main inventory query
        $query = Inventory::query();

        // Apply search if present
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Apply availability filter
        if ($availability = $request->input('availability')) {
            $query->where('availability', $availability);
        }

        // Apply sorting
        $sortField = $request->input('sort', 'item_name');
        $sortDirection = $request->input('direction', 'asc');

        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['item_name', 'serial_number', 'quantity', 'availability'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $items = $query->paginate(10)->withQueryString();

        return view('admin.inventory.inventory', compact(
            'items',
            'sortField',
            'sortDirection',
            'totalItems',
            'totalValue',
            'outOfStockCount',
            'lowStockItems',
            'lowStockCount'
        ));
    }


    public function addItem()
    {
        $branches = Branch::all();
        return view('admin.inventory.add-item', compact('branches'));
    }

    public function storeItem(Request $request)
    {
        $validatedData = $request->validate([
            'item_name' => 'required|string',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:0',
            'serial_number' => 'required|string|max:10|unique:inventories,serial_number',
            'cost_per_item' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $minimumQuantity = $validatedData['quantity'] * 0.30;
        $availability = $this->determineAvailability($validatedData['quantity'], $minimumQuantity);
        $totalValue = $validatedData['quantity'] * $validatedData['cost_per_item'];

        $item = Inventory::create([
            'item_name' => $validatedData['item_name'],
            'branch_id' => $validatedData['branch_id'],
            'quantity' => $validatedData['quantity'],
            'minimum_quantity' => $minimumQuantity,
            'serial_number' => $validatedData['serial_number'],
            'cost_per_item' => $validatedData['cost_per_item'],
            'availability' => $availability,
            'total_value' => $totalValue,
            'notes' => $validatedData['notes'],
        ]);

        AuditLog::create([
            'action' => 'Create',
            'model_type' => 'New item added',
            'model_id' => $item->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('inventory')->with('success', 'Item added successfully!');
    }

    private function determineAvailability($quantity, $minimumQuantity)
    {
        if ($quantity <= 0) {
            return 'out-of-stock';
        } elseif ($quantity < $minimumQuantity) {
            return 'to-order';
        } else {
            return 'available';
        }
    }

    public function editItem($id)
    {
        $item = Inventory::findOrFail($id);

        $branches = Branch::all();
        return view('admin.inventory.edit-item', compact('item', 'branches'));
    }

    public function updateItem(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);

        $validatedData = $request->validate([
            'item_name' => 'required|string',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:0',
            'serial_number' => 'required|string|max:10|unique:inventories,serial_number',
            'cost_per_item' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);


        $minimumQuantity = $item->minimum_quantity;
        $availability = $this->determineAvailability($validatedData['quantity'], $minimumQuantity);
        $totalValue = $validatedData['quantity'] * $validatedData['cost_per_item'];

        $item->update([
            'item_name' => $validatedData['item_name'],
            'branch_id' => $validatedData['branch_id'],
            'quantity' => $validatedData['quantity'],
            'minimum_quantity' => $minimumQuantity,
            'serial_number' => $validatedData['serial_number'],
            'cost_per_item' => $validatedData['cost_per_item'],
            'availability' => $availability,
            'total_value' => $totalValue,
            'notes' => $validatedData['notes'],
        ]);

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Item information updated',
            'model_id' => $item->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('inventory')->with('success', 'Item updated successfully!');
        session()->flash('success', 'Item updated successfully!');
    }

    public function deleteItem(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);

        $item->delete();

        AuditLog::create([
            'action' => 'Delete',
            'model_type' => 'Item deleted',
            'model_id' => $item->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('inventory')->with('success', 'Item deleted successfully!');
        session()->flash('success', 'Item deleted successfully!');
    }


}
