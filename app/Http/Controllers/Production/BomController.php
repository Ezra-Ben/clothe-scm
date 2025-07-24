<?php

namespace App\Http\Controllers\Production;

use App\Models\Bom;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BomController extends Controller
{
    public function index()
    {
        $boms = Bom::with('product')->get();
        return view('production.bom.index', compact('boms'));
    }

    public function create()
    {
        $products = Product::all();
        $rawMaterials = RawMaterial::all();
        return view('production.bom.create', compact('products', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id|unique:boms,product_id',
            'bom_items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'bom_items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $bom = Bom::create([
            'product_id' => $request->product_id,
            'version' => 'v1',
            'description' => 'Auto-generated BOM for ' . Product::find($request->product_id)->name,
        ]);

        foreach ($request->bom_items as $item) {
            $bom->items()->create([
                'raw_material_id' => $item['raw_material_id'],
                'quantity' => $item['quantity'],
                'unit_of_measure' => RawMaterial::find($item['raw_material_id'])->unit,
            ]);
        }

        return redirect()->route('boms.index')->with('success', 'BOM created successfully.');
    }

    public function edit(Bom $bom)
    {
        $products = Product::all();
        $rawMaterials = RawMaterial::all();
        $bom->load('items.rawMaterial');

        return view('production.bom.edit', compact('bom', 'products', 'rawMaterials'));
    }

    public function update(Request $request, Bom $bom)
    {
        $validated = $request->validate([
            'bom_items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'bom_items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        // Optional: update BOM metadata
        $bom->update([
            'description' => 'Updated BOM for ' . $bom->product->name,
        ]);

        // Delete existing items and recreate
        $bom->items()->delete();

        foreach ($request->bom_items as $item) {
            $bom->items()->create([
                'raw_material_id' => $item['raw_material_id'],
                'quantity' => $item['quantity'],
                'unit_of_measure' => RawMaterial::find($item['raw_material_id'])->unit,
            ]);
        }

        return redirect()->route('boms.index')->with('success', 'BOM updated successfully.');
    }

    public function destroy(Bom $bom)
    {
        $bom->items()->delete(); // delete related items
        $bom->delete();

        return redirect()->route('boms.index')->with('success', 'BOM deleted.');
    }
}
