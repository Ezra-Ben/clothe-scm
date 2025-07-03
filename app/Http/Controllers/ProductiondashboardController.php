<?php
namespace App\Http\Controllers;

use App\Models\ProductionBatch;
use App\Services\ProductionService;
use App\Models\Product;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductionDashboardController extends Controller
{
      protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }
    public function complete(ProductionOrder $productionOrder)
    {
        try {
            // This calls the ProductionService, which now interacts with InventoryService
            $this->productionService->completeProductionOrder($productionOrder);
            return back()->with('success', 'Production order completed and finished goods updated!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error completing order: ' . $e->getMessage());
        }
        $this->service->completeBatch($id);
        return redirect()->route('production.dashboard')->with('success', 'Batch completed and inventory notified.'); 
    if ($productionOrder->status !== 'In Progress') {
            return back()->with('error', 'Only in-progress orders can be completed.');
        }

        DB::transaction(function () use ($productionOrder) {
            //Update production order status
            $productionOrder->status = 'Completed';
            $productionOrder->completed_at = now();
            $productionOrder->save();

            // Receive finished goods to inventory
            $finishedGood = FinishedGoodsInventory::firstOrNew(['product_id' => $productionOrder->product_id]);
            $finishedGood->quantity += $productionOrder->quantity;
            $finishedGood->save();

           
            foreach ($productionOrder->orderItems as $orderItem) {
                if ($orderItem->status === 'MTO') { // Only mark MTO items as fulfilled by this production
                    $orderItem->status = 'Fulfilled';
                    $orderItem->save();
                }
            }
        });
       return back()->with('success', 'Production order completed and finished goods updated!');
    }

    // You could add a method here to "start" a production order, which would
    // call ProductionService->startProductionOrder(), deducting raw materials.
    // Example:
    public function start(ProductionOrder $productionOrder)
    {
        try {
            $this->productionService->startProductionOrder($productionOrder);
            return back()->with('success', 'Production order started and raw materials deducted!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error starting production: ' . $e->getMessage());
        }
    }
     public function index(Request $request)
    {
        $batches = ProductionBatch::with('product')
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->has('product'), fn($q) => $q->where('product_id', $request->product))
            ->orderBy('is_urgent', 'desc')
            ->orderBy('scheduled_at', 'asc')
            ->paginate(10);
       
            $batchQuery = ProductionBatch::with('product')
        ->when($request->search, function ($query, $search) {
            $query->where('batch_code', 'like', '%' . $search . '%');
        })
        ->when($request->status, function ($query, $status) {
            $query->where('status', $status);
        })
        ->when($request->product, function ($query, $product) {
            $query->where('product_id', $product);
        })
        ->orderBy('scheduled_at', 'asc'); //  FIFO sorting here
            $batches = $batchQuery->paginate(10);
 return view('product.production_dashboard', [
            'batches' => $batches,
            'totalBatchesCount' => ProductionBatch::count(),
            'pendingBatchesCount' => ProductionBatch::where('status', 'pending')->count(),
            'inProgressBatchesCount' => ProductionBatch::where('status', 'in_progress')->count(),
            'completedBatchesCount' => ProductionBatch::where('status', 'completed')->count(),
            'productionOrders'
        ]);
    $totalBatches = ProductionOrder::count();
    $pendingBatches = ProductionOrder::where('status', 'Pending')->count();
    $inProgressBatches = ProductionOrder::where('status', 'In Progress')->count();
    $completedBatches = ProductionOrder::where('status', 'Completed')->count();
    $productionOrders = ProductionOrder::with('product')   
              ->orderBy('scheduled_at', 'desc') 
              ->paginate(10); 
              dd ($productionOrders);
               return view('product.production_dashboard', compact(
        'totalBatches', 'pendingBatches', 'inProgressBatches',
        'completedBatches', 'productionOrders'
    ));
   
  
    }
public function startProduction(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'urgent' => 'nullable|boolean',
    ]);

    $batch = ProductionBatch::create([
        'batch_code' => 'BATCH-' . strtoupper(uniqid()),
        'product_id' => $validated['product_id'],
        'status' => 'pending',
        'is_urgent' => $validated['urgent'] ?? false,
        'scheduled_at' => now()->addDays(1),
        'packaging_status' => 'unassigned',

    ]);

    return response()->json([
        'message' => 'Production batch created successfully.',
        'data' => $batch
    ], 201);
}
public function dashboard(request $request){
         $query = ProductionBatch::with('product');
if($request->filled('search_batch_code')){
    $query->where('batch_code', 'like', '%' .$request->input('search_batch_code') . '%');
}
if($request->filled('status_filter')){
    $query->where('status', $request->input('status_filter') . '%');
}
if($request->filled('product_filter')){
    $query->where('product_id',$request->input('product_filter') . '%');
}
      //if (Gate::denies('access-production')) {
       // abort(403); // Block access if not admin/production_manager
    //}
 
  $batches =     $query->paginate(10)->appends(request()->query());
  $products = Product::all();
   $productionOrders = ProductionOrder::with('product')   
              ->orderBy('scheduled_at', 'desc')->paginate(10);
    $batches = ProductionBatch::with('product')->paginate(10);
    $totalBatchesCount = ProductionBatch::count();
    $pendingBatchesCount = ProductionBatch::where('status','pending')->count();
    $inProgressBatchesCount =ProductionBatch::where('status','in_progress')->count();
    $completedBatchesCount =ProductionBatch::where('status','completed')->count();

    return view('product.production_dashboard', compact(
        'batches',
        'totalBatchesCount','pendingBatchesCount','inProgressBatchesCount','completedBatchesCount','productionOrders'));
}

}
