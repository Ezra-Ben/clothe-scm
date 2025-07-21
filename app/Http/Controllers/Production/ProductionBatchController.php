<?php

namespace App\Http\Controllers\Production;

use App\Models\ProductionBatch;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductionBatchController extends Controller
{
    /**
     * Show the form to create a new production batch for a specific order.
     */
    public function create(Request $request)
    {
        $orderId = $request->get('production_order_id');
        $productionOrder = ProductionOrder::findOrFail($orderId);

        // Optional: prevent duplicate batch creation
        if ($productionOrder->productionBatch) {
            return redirect()->route('production_orders.show', $orderId)
                ->with('error', 'Batch already exists for this production order.');
        }

        return view('production.production_batches.create', compact('productionOrder'));
    }

    /**
     * Store a newly created production batch in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'produced_quantity'   => 'required|integer|min:1',
            'status'              => 'required|in:pending,in_progress,completed,failed',
        ]);

        // Set started_at and (optionally) completed_at
        $batchData = $validated;
        $batchData['started_at'] = now();

        if ($validated['status'] === 'completed') {
            $batchData['completed_at'] = now();
        }

        $batch = ProductionBatch::create($batchData);

        return redirect()
            ->route('production_orders.show', $batch->production_order_id)
            ->with('success', 'Production batch created successfully.');
    }

    /**
     * Show the form for editing the specified production batch.
     */
    public function edit(ProductionBatch $productionBatch)
    {
        if ($productionBatch->status === 'completed') {
            return redirect()
                ->route('production_orders.show', $productionBatch->production_order_id)
                ->with('error', 'Cannot edit a completed production batch.');
        }

        $batch = $productionBatch;
        return view('production.production_batches.edit', compact('batch'));
    }

    /**
     * Update the specified production batch in storage.
     */
    public function update(Request $request, ProductionBatch $productionBatch)
    {
        $validated = $request->validate([
            'produced_quantity' => 'required|integer|min:1',
            'status'            => 'required|in:pending,in_progress,completed,failed',
        ]);

        if ($validated['status'] === 'completed' && !$productionBatch->completed_at) {
            $validated['completed_at'] = now();
        }

        $productionBatch->update($validated);

        return redirect()
            ->route('production_orders.show', $productionBatch->production_order_id)
            ->with('success', 'Production batch updated successfully.');
    }
}
