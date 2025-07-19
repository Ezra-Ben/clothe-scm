<?php

namespace App\Http\Controllers\Production;


use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\QualityControl;
use App\Models\ProductionOrder;
use App\Models\ProductionBatch;
use App\Services\ProductionService;
use App\Http\Controllers\Controller;

class ProductionOrderController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    public function index()
    {
        $batches = ProductionBatch::orderBy('id','desc')->get();
        $orders = ProductionOrder::orderBy('id','desc')->take(5)->get();

        $totalBatches = $batches->count();
        $completedBatches = $batches->where('status','completed')->count();
        $pendingBatches = $batches->where('status','pending')->count();

        $qcPassed = QualityControl::whereIn('production_batch_id', $batches->pluck('id'))
            ->where('result','passed')->count();
        $qcFailed = QualityControl::whereIn('production_batch_id', $batches->pluck('id'))
            ->where('result','failed')->count();

        $totalQC = $qcPassed + $qcFailed;
        $qcPassedPercent = $totalQC ? ($qcPassed / $totalQC * 100) : 0;
        $qcFailedPercent = 100 - $qcPassedPercent;

        return view('dashboard', compact(
            'batches','orders','totalBatches','completedBatches','pendingBatches',
            'qcPassed','qcFailed','qcPassedPercent','qcFailedPercent'
    ));
    }

    public function create()
    {
        $products = Product::all();
        return view('production_orders.create', compact('products'));
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

        return redirect()->route('production_orders.index')->with('success', 'Production Order created!');
    }

    public function show($id)
    {
        $productionOrder = ProductionOrder::with(['product', 'order'])->findOrFail($id);
        $rawMaterials = app(ProductionService::class)->getRawMaterialsForProductionOrder($productionOrder->id);

        return view('production_orders.show', compact('productionOrder', 'rawMaterials'));
    }

    public function complete($id)
    {
        $this->productionService->completeProduction($id);

        return redirect()->route('production_orders.index')->with('success', 'Production Order completed!');
    }
}
