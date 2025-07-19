<?php

namespace App\Http\Controllers\Inventory;

use App\Models\RawMaterial;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    // List all inventory records
    public function index(Request $request)
    {
        $productSearch = $request->input('product_search');
        $rawSearch = $request->input('raw_search');

        $inventories = Inventory::with('product')
        ->when($productSearch, function ($query) use ($productSearch) {
            $query->whereHas('product', function ($q) use ($productSearch) {
                $q->where('name', 'like', '%' . $productSearch . '%');
            });
        })
        ->get();

        $rawMaterials = RawMaterial::when($rawSearch, function ($query) use ($rawSearch) {
            $query->where('name', 'like', '%' . $rawSearch . '%')
                  ->orWhere('sku', 'like', '%' . $rawSearch . '%');
        })->get();
        return view('inventory.index', compact('inventories', 'rawMaterials'));
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

    // Show form to create new inventory
    public function create()
    {
        $products = Product::all();
        return view('inventory.create', compact('products'));
    }

    // Store new inventory record
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:inventories,product_id',
            'quantity_on_hand' => 'required|numeric|min:0',
            'quantity_reserved' => 'nullable|numeric|min:0',
        ]);

        Inventory::create([
            'product_id' => $request->product_id,
            'quantity_on_hand' => $request->quantity_on_hand,
            'quantity_reserved' => $request->quantity_reserved ?? 0,
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventory created successfully.');
    }
}
