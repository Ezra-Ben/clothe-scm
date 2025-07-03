<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;

class ProductionOrderApiController extends Controller
{
    /**
     * Display a listing of the production orders.
     */
    public function index()
    {
        // Return production orders with related product and status
        $productionOrders = ProductionOrder::with('product')->get();
        return response()->json($productionOrders);
    }

    /**
     * Display the specified production order.
     */
    public function show(ProductionOrder $productionOrder)
    {
        // Return a specific production order with all its loaded relationships
        $productionOrder->load('product', 'bom.bomItems.rawMaterial', 'orderItems.product', 'orderItems.order');
        return response()->json($productionOrder);
    }
}

