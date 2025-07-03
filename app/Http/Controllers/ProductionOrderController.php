<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use Illuminate\Http\Request;

class ProductionOrderController extends Controller
{
    /**@return \Illuminate\Http\Response**/
    public function index(){
        
        return view('ProductionOrderDetail');
    }
    public function show(ProductionOrder $productionOrder)
    {
        // Business Logic: Eager load all necessary relationships for the detail view
        $productionOrder->load([
            'product',
            'bom.bomItems.rawMaterial', // Load BOM, its items, and their raw materials
            'orderItems.product',       // Load linked order items and their products
            'orderItems.order'          // Load the parent order for customer info
        ]);

        return view('ProductionOrderDetail', compact('productionOrder'));
    }

   
}


