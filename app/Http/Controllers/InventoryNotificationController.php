<?php

namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use App\Services\InventoryService;

class InventoryNotificationController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function showInventory()
    {
        $items = $this->inventoryService->getAll();
        return view('production.inventory_status', compact('items'));
    }

    public function notifyStockUse($batchId)
    {
        $batch = ProductionBatch::find($batchId);

        if (!$batch) {
            return back()->withErrors(['message' => 'Batch not found.']);
        }

        $success = $this->inventoryService->updateStock($batch->product_id, 1);

        if (!$success) {
            return back()->withErrors(['message' => 'Failed to update stock.']);
        }

        return back()->with('success', 'Stock updated successfully.');
    }
    public function status()
{
    $items = \App\Models\InventoryItem::with('product')->get();
    return view('inventory_status', compact('items'));
}

}
