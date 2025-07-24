<?php

namespace App\Http\Controllers\Production;

use App\Models\Bom;
use App\Models\BomItem;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BomItemController extends Controller
{
    public function index(Bom $bom)
    {
        $bom->load(['product', 'items.rawMaterial']);
        return view('production.bom.bom_items.index', compact('bom'));
    }

    public function create(Bom $bom)
    {
        $rawMaterials = RawMaterial::all();
        return view('production.bom.bom_items.form', compact('bom', 'rawMaterials'));
    }

    public function store(Request $request, Bom $bom)
    {
        $validated = $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_of_measure' => 'required|string|max:255',
        ]);

        $validated['bom_id'] = $bom->id;

        BomItem::create($validated);

        return redirect()->route('boms.items.index', $bom->id)->with('success', 'BOM item added successfully.');
    }

    public function edit(Bom $bom, BomItem $item)
    {
        $rawMaterials = RawMaterial::all();
        return view('production.bom.bom_items.form', [
            'bom' => $bom,
            'bomItem' => $item,
            'rawMaterials' => $rawMaterials
        ]);
    }

    public function update(Request $request, Bom $bom, BomItem $item)
    {
        $validated = $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_of_measure' => 'required|string|max:255',
        ]);

        $item->update($validated);

        return redirect()->route('boms.items.index', $bom->id)->with('success', 'BOM item updated.');
    }

    public function destroy(Bom $bom, BomItem $item)
    {
        $item->delete();

        return redirect()->route('boms.items.index', $bom->id)->with('success', 'BOM item deleted.');
    }
}
