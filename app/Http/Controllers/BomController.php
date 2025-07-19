<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // For transactions

class BomController extends Controller
{
    /**
     * Display a listing of the BOMs.
     */
    public function index()
    {
        // Business Logic: Fetch all BOMs with their associated product and count of bom items.
        $boms = Bom::with('product', 'bomItems')->get();
        return view('production.BomList', compact('boms'));
    }

    /**
     * Show the form for creating a new BOM.
     */
    public function create()
    {
        $products = Product::all();
        $rawMaterials = RawMaterial::all();
        return view('production.BomForm', compact('products', 'rawMaterials'));
    }

    /**
     * Store a newly created BOM in storage.
     */
    public function store(Request $request)
    {
        // Business Logic: Validation
        $request->validate([
            'product_id' => ['required', 'exists:products,id', Rule::unique('boms', 'product_id')],
            'bom_items' => 'required|array|min:1',
            'bom_items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'bom_items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        // Business Logic: Create BOM and its items in a transaction
        DB::transaction(function () use ($request) {
            $bom = Bom::create(['product_id' => $request->product_id]);
            foreach ($request->bom_items as $item) {
                $bom->bomItems()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()->route('boms.index')->with('success', 'BOM created successfully!');
    }

    /**
     * Display the specified BOM.
     */
    public function show(Bom $bom)
    {
        $bom->load('product', 'bomItems.rawMaterial');
        return view('production.BomForm', compact('bom', 'products', 'rawMaterials'))->with('is_show', true);
    }

    /**
     * Show the form for editing the specified BOM.
     */
    public function edit(Bom $bom)
    {
        $products = Product::all();
        $rawMaterials = RawMaterial::all();
        $bom->load('bomItems.rawMaterial');
        return view('production.BomForm', compact('bom', 'products', 'rawMaterials'));
    }

    /**
     * Update the specified BOM in storage.
     */
    public function update(Request $request, Bom $bom)
    {
        // Business Logic: Validation
        $request->validate([
            'bom_items' => 'required|array|min:1',
            'bom_items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'bom_items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        // Business Logic: Update BOM items in a transaction
        DB::transaction(function () use ($request, $bom) {
            $bom->bomItems()->delete(); // Clear existing BOM items
            foreach ($request->bom_items as $item) {
                $bom->bomItems()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()->route('boms.index')->with('success', 'BOM updated successfully!');
    }

    /**
     * Remove the specified BOM from storage.
     */
    public function destroy(Bom $bom)
    {
        // Business Logic: Delete BOM and cascade delete its items
        $bom->delete();
        return redirect()->route('boms.index')->with('success', 'BOM deleted successfully!');
    }
}
