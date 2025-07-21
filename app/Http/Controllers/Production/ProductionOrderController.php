<?php

namespace App\Http\Controllers\Production;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\QualityControl;
use App\Models\ProductionOrder;
use App\Models\ProductionBatch;
use App\Services\ProductionService;
use App\Http\Controllers\Controller;
use App\Notifications\ProductionOrderCreated;

class ProductionOrderController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    public function index()
    {
        $allBatches = ProductionBatch::all();
        $batches = ProductionBatch::orderBy('id','desc')->take(5)->get();
        $orders = ProductionOrder::orderBy('id','desc')->take(5)->get();

        $totalBatches = $allBatches->count();
        $completedBatches = $allBatches->where('status','completed')->count();
        $pendingBatches = $allBatches->where('status','pending')->count();

        $qcPassed = QualityControl::whereIn('production_batch_id', $allBatches->pluck('id'))
            ->where('status','passed')->count();
        $qcFailed = QualityControl::whereIn('production_batch_id', $allBatches->pluck('id'))
            ->where('status','failed')->count();

        $totalQC = $qcPassed + $qcFailed;
        $qcPassedPercent = $totalQC ? ($qcPassed / $totalQC * 100) : 0;
        $qcFailedPercent = 100 - $qcPassedPercent;

        return view('production.index', compact(
            'batches','orders','totalBatches','completedBatches','pendingBatches',
            'qcPassed','qcFailed','qcPassedPercent','qcFailedPercent'
        ));
    }

    public function create()
    {
        $products = Product::all();
        return view('production.production_orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $this->productionService->createProductionOrderForStock(
            $validated['product_id'],
            $validated['quantity']
        );

        $productionManager = User::whereHas('roles', function ($query) {
            $query->where('name', 'production_manager');
            })->first();

        if ($productionManager) {
            $productionManager->notify(new ProductionOrderCreated($productionOrder));
        }

        return redirect()->route('inventory.index')->with('success', 'Production Order created!');
    }

    public function show($id)
    {
        $productionOrder = ProductionOrder::with(['product', 'order'])->findOrFail($id);

        $rawMaterialQuantities = app(ProductionService::class)->getRawMaterialsForProductionOrder($productionOrder->id);

        $rawMaterials = RawMaterial::whereIn('id', array_keys($rawMaterialQuantities))->get();

        foreach ($rawMaterials as $material) {
            $material->required_quantity = $rawMaterialQuantities[$material->id];
        }

        return view('production.production_orders.show', compact('productionOrder', 'rawMaterials'));
    }

    public function complete($id)
    {
        $this->productionService->completeProduction($id);

        return redirect()->route('production_orders.index')->with('success', 'Production Order completed!');
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');
        $productName = $request->input('product');

        // Global Metrics (Unfiltered)
        $allOrders = ProductionOrder::with('product')->get();
        $totalOrders = $allOrders->count();
        $completedOrders = $allOrders->where('status', 'completed')->count();
        $totalQuantityProduced = $allOrders->where('status', 'completed')->sum('quantity');

        $allBatches = ProductionBatch::all();
        $qcPassed = QualityControl::where('status', 'passed')->count();
        $qcFailed = QualityControl::where('status', 'failed')->count();
        $totalQC = $qcPassed + $qcFailed;
        $qcPassedPercent = $totalQC ? round(($qcPassed / $totalQC) * 100, 2) : 0;

        // 2. Filtered Orders Table
        $ordersQuery = ProductionOrder::with('product');

        if ($startDate) {
            $ordersQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $ordersQuery->whereDate('updated_at', '<=', $endDate);
        }
        if ($status) {
            $ordersQuery->where('status', $status);
        }
        if ($productName) {
            $ordersQuery->whereHas('product', function ($query) use ($productName) {
                $query->where('name', 'like', '%' . $productName . '%');
            });
        }

        $orders = $ordersQuery->get();

        // Filtered Batches Table
        $batchesQuery = ProductionBatch::with('productionOrder.product');

        if ($startDate) {
            $batchesQuery->whereDate('started_at', '>=', $startDate);
        }
        if ($endDate) {
            $batchesQuery->whereDate('completed_at', '<=', $endDate);
        }
        if ($status) {
            $batchesQuery->where('status', $status);
        }

        $batches = $batchesQuery->get();

        return view('production.report', compact(
            'totalOrders', 'completedOrders', 'totalQuantityProduced', 'qcPassedPercent',
            'orders', 'batches', 'startDate', 'endDate', 'status', 'productName'
        ));
    }

}
