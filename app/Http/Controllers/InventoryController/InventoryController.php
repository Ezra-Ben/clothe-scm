<?php
namespace App\Http\Controllers\InventoryController;

use App\Services\InventoryService;
use Illuminate\Http\Request;
use App\Services\ProcurementService;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\ProcurementRequest;
use App\Http\Controllers\Controller;


class InventoryController extends Controller
{
    protected $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->inventory = $inventory;
    }
    public function index()
{
    $inventories = Inventory::with('product')->get();
    return view('InventoryProcurement.Inventory', compact('inventories'));
}
    public function handleOrderRequest(Request $request)
{
    $productId = $request->input('product_id');
    $quantity = $request->input('quantity');


    if ($this->inventory->hasEnoughStock($productId, $quantity)) {
        $this->inventory->reserveStock($productId, $quantity);
        return response()->json(['status' => 'reserved']);
    }

    
    $missing = $this->inventory->canProduce($productId, $quantity);
    if (empty($missing)) {
        // 3. Trigger production
        $this->inventory->triggerProduction($productId, $quantity);
        return response()->json(['status' => 'production_triggered']);
    }

    // 4. Trigger procurement for missing raw materials
    foreach ($missing as $rawId => $qty) {
        app(ProcurementService::class)->createProcurementRequest($rawId, $qty);
    }
    return response()->json(['status' => 'procurement_triggered', 'missing' => $missing]);
}

    public function checkProductExists($productId)
    {
        $exists = $this->inventory->checkProductExists($productId);
        return response()->json(['exists' => $exists]);
    }

    public function checkProductQuantity($productId, Request $request)
    {
        $quantity = $request->input('quantity', 1);
        $enough = $this->inventory->hasEnoughStock($productId, $quantity);
        return response()->json(['enough_stock' => $enough]);
    }

    public function reserveProduct($productId, Request $request)
    {
        $quantity = $request->input('quantity', 1);
        $reserved = $this->inventory->reserveStock($productId, $quantity);
        return response()->json(['reserved' => $reserved]);
    }

    public function increaseStock($productId, Request $request)
    {
        $quantity = $request->input('quantity', 1);
        $inventory = $this->inventory->increaseStock($productId, $quantity);
        return response()->json(['inventory' => $inventory]);
    }
    public function dashboard()
{
    $supplierCount =Supplier::count();
    $pendingProcurements =ProcurementRequest::where('status', 'pending')->count();
    $inventoryCount =Inventory::count();
    $notificationCount = auth()->user()->notifications()->count();

    return view('InventoryProcurement.Dashboard', compact(
        'supplierCount',
        'pendingProcurements',
        'inventoryCount',
        'notificationCount'
    ));
}
}