<?php
namespace App\Http\Controllers\InventoryController;

use App\Services\InventoryService;
use Illuminate\Http\Request;
use App\Services\ProcurementService;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\ProcurementRequest;
use App\Http\Controllers\Controller;
use App\Models\Product;


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
    $customerId = $request->input('customer_id');
    
     $order = \App\Models\Order::create([
        'product_id' => $productId,
        'customer_id' => $customerId,
        'quantity' => $quantity,
        'status' => 'pending',
    ]);

    if ($this->inventory->hasEnoughStock($productId, $quantity)) {
        $this->inventory->reserveStock($productId, $quantity);
        return response()->json(['status' => 'reserved']);
        $order->save();
        return response()->json([
            'order_id' => $order->id,
            'status' => 'reserved',
            'message' => 'Order successfully received. Product is available and reserved for delivery.'
        ]);
    }

    
    $missing = $this->inventory->canProduce($productId, $quantity);
    if (empty($missing)) {
        // 3. Trigger production
        $this->inventory->triggerProduction($productId, $quantity,$order->id);
        $order->status = 'production';
        $order->save();

        return response()->json([
            'order_id' => $order->id,
            'status' => 'production_triggered',
            'message' => 'Order received. Product is being produced for your order.'
        ]);
    }

    // 4. Trigger procurement for missing raw materials
    foreach ($missing as $rawId => $qty) {
        app(ProcurementService::class)->createProcurementRequest($rawId, $qty,$order->id);
    }
    $order->status = 'procurement';
    $order->save();
   return response()->json([
        'order_id' => $order->id,
        'status' => 'procurement_triggered',
        'missing' => $missing,
        'message' => 'Order received. Procurement for missing materials has started for your order.'
    ]);
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
        public function orderRequests()
        {
        // Assuming you have an OrderRequest model, or use ProcurementRequest if that's what you mean
        $orderRequests = \App\Models\Order::orderByDesc('created_at')->get();
        return view('InventoryProcurement.OrderRequests', compact('orderRequests'));
        }

    public function showOrderRequest($id)
   {
    $order = \App\Models\Order::findOrFail($id);

    // Determine status message
    if ($order->status === 'reserved') {
        $message = 'Product has been reserved.';
    } elseif ($order->status === 'production') {
        $message = 'Production is in progress.';
    } elseif ($order->status === 'procurement') {
        $message = 'Procurement request is in progress.';
    } else {
        $message = 'Order status: ' . ucfirst($order->status);
    }

    return view('InventoryProcurement.OrderRequestDetail', compact('order', 'message'));
    }
    public function addProduct(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);
    $inventory = Inventory::firstOrCreate(
        ['product_id' => $request->product_id],
        ['quantity' => 0]
    );
    $inventory->quantity += $request->quantity;
    $inventory->save();

    return redirect()->back()->with('success', 'Product added to inventory.');
    }
    public function deleteProduct($id)
{
    $inventory = Inventory::findOrFail($id);
    $inventory->delete();

    return redirect()->back()->with('success', 'Product removed from inventory.');
}
public function productionCompleted(Request $request)
{
    if ($request->header('X-PMS-Token') !== config('services.pms.token')) {
        abort(403, 'Unauthorized');
    }
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity_produced' => 'required|integer|min:1',
        'order_id' => 'required|exists:orders,id',
        // Optionally: 'order_id' => 'sometimes|exists:order_requests,id'
    ]);

    // Use your InventoryService to increase stock
    app(\App\Services\InventoryService::class)->increaseStock(
        $request->product_id,
        $request->quantity_produced
    );
    $order = \App\Models\Order::find($request->order_id);
    if ($order) {
        $order->status = 'ready'; // or 'delivered', as appropriate
        $order->save();
    }

    return response()->json(['status' => 'success', 'message' => 'Production completed, stock updated,order status updated.']);
    }
    public function createProductForm()
{
    return view('InventoryProcurement.CreateProduct');
}

    public function storeProduct(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:100|unique:products,sku',
        'type' => 'required|in:raw,finished',
        // Add other fields as needed
    ]);

    Product::create([
        'name' => $request->name,
         'sku' => $request->sku,
        'type' => $request->type,
        // Add other fields as needed
    ]);

    return redirect()->route('inventory.index')->with('success', 'Product created successfully.');
}
}
