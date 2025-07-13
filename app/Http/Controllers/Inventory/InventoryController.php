<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    // List all inventory records
    public function index()
    {
        $inventories = Inventory::with('product')->get();
        return view('inventory.index', compact('inventories'));
    }

    // (Optional) Show form to edit stock manually
    public function edit($id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);
        return view('inventory.edit', compact('inventory'));
    }

    // (Optional) Update stock manually
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity_on_hand' => 'required|numeric|min:0',
            'quantity_reserved' => 'required|numeric|min:0',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->quantity_on_hand = $request->quantity_on_hand;
        $inventory->quantity_reserved = $request->quantity_reserved;
        $inventory->save();

        return redirect()->route('inventory.index')->with('success', 'Inventory updated successfully.');
    }
}
