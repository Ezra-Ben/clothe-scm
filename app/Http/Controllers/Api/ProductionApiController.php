<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Services\ProductionService;
use Illuminate\Support\Facades\log;

class ProductionApiController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    public function index()
    {
        return ProductionBatch::with(['product', 'order'])->get();
    }

    public function complete($id)
{
    $batch = ProductionBatch::with('product')->findOrFail($id);

    //Mark batch as completed
    $batch->status = 'completed';
    $batch->save();

    //Notify inventory system 
    $this->notifyInventory($batch);

    return response()->json([
        'message' => 'Production batch marked as completed and inventory notified.',
        'batch' => $batch
    ]);
}

    protected function notifyInventory($batch)
    {
        Log::info('Inventory notified of completed batch', [
            'product_id' => $batch->product_id,
            'product_name' => $batch->product->product_name ?? 'N/A',
            'quantity' => $batch->productionRequest->quantity ?? 1,
            'batch_id' => $batch->id,
        ]);

    public function startFromOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id'
        ]);

        $result = $this->productionService->startBatchFromOrder(
            $request->order_id,
            $request->product_id
        );

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 422);
        }

        return response()->json($result);
    }
}
}


