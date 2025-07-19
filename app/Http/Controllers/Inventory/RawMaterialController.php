<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RawMaterial;

class RawMaterialController extends Controller
{
    public function create()
    {
        return view('inventory.rawmaterials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:raw_materials,sku',
            'unit_of_measure' => 'required|string|max:50',
            'quantity_on_hand' => 'required|numeric|min:0',
            'reorder_point' => 'required|numeric|min:0',
        ]);

        RawMaterial::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Raw material added successfully.');
    }

    public function edit($id)
    {
        $material = RawMaterial::findOrFail($id);
        return view('inventory.rawmaterials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = RawMaterial::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:raw_materials,sku,' . $material->id,
            'unit_of_measure' => 'required|string|max:50',
            'quantity_on_hand' => 'required|numeric|min:0',
            'reorder_point' => 'required|numeric|min:0',
        ]);

        $material->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Raw material updated successfully.');
    }

    public function destroy($id)
    {
        $material = RawMaterial::findOrFail($id);
        $material->delete();

        return redirect()->route('inventory.index')->with('success', 'Raw material deleted successfully.');
    }
}
