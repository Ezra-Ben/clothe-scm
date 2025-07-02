<?php

namespace App\Http\Controllers;

use App\Models\FinishedGoodsInventory;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display the inventory status dashboard including finished goods and raw materials.
     */
    public function index()
    {
        // Business Logic: Fetch all finished goods with product details
        $finishedGoods = FinishedGoodsInventory::with('product')->get();
        // Business Logic: Fetch all raw materials
        $rawMaterials = RawMaterial::all();

        return view('InventoryStatus', compact('finishedGoods', 'rawMaterials'));
    }
}
