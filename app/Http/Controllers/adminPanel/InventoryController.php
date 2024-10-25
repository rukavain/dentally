<?php

namespace App\Http\Controllers\adminPanel;

use App\Models\Branch;
use App\Models\AuditLog;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{

    public function inventory()
    {
        $items = Inventory::all();

        return view('admin.inventory.inventory', compact('items'));
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
            'serial_number' => 'required|string|max:10',
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
            'serial_number' => 'required|string|max:10',
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

    public function deleteItem($id)
    {
        $item = Inventory::findOrFail($id);

        $item->delete();

        AuditLog::create([
            'action' => 'Delete',
            'model_type' => 'Item deleted',
            'model_id' => $inventory->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('inventory')->with('success', 'Item deleted successfully!');
        session()->flash('success', 'Item deleted successfully!');
    }


}
