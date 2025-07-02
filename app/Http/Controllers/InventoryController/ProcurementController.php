<?php
namespace App\Http\Controllers\InventoryController;

use App\Services\ProcurementService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProcurementRequest;
use App\Notifications\ProcurementRequestApproved;
use App\Notifications\ProcurementRequestRejected;
use Illuminate\Support\Facades\Auth;

class ProcurementController extends Controller
{
    protected $procurement;

    public function __construct(ProcurementService $procurement)
    {
        $this->procurement = $procurement;
    }

     public function index()
{
    $requests = ProcurementRequest::with(['product', 'supplier.vendor'])->orderByDesc('created_at')->get();
    return view('InventoryProcurement.Procurement_Request', compact('requests'));
}
    public function createRequest(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $procurement = $this->procurement->createProcurementRequest(
            $request->product_id,
            $request->quantity
        );
        return response()->json($procurement);
    }

    public function logDelivery(Request $request, $procurementRequestId)
    {
        $request->validate([
            'delivered_quantity' => 'required|integer|min:1',
        ]);
        $delivery = $this->procurement->logDelivery($procurementRequestId, $request->delivered_quantity);
        return response()->json($delivery);
    }
    public function approve($id)
    {
        try {
            $request = ProcurementRequest::findOrFail($id);
            if ($request->status !== 'pending') {
                return response()->json(['error' => 'Request already processed.'], 400);
            }
            $request->status = 'approved';
            $request->approved_by = Auth::id();
            $request->approved_at = now();
            $request->save();
            if ($request->supplier && $request->supplier->user) {
            $request->supplier->user->notify(new ProcurementRequestApproved($request));
            return redirect()->back()->with('success', 'Procurement request approved.');
}
            return response()->json(['status' => 'approved']);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Procurement approval failed: ' . $e->getMessage());
            return response()->json(['error' => 'Approval failed. Please try again.'], 500);
        }
    }
    

    public function reject($id)
    {
        $request = ProcurementRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->approved_by = Auth::id();
        $request->approved_at = now();
        $request->save();
         if ($request->supplier && $request->supplier->user) {
            $request->supplier->user->notify(new ProcurementRequestApproved());
        }
        return redirect()->back()->with('success', 'Procurement request rejected.');
    }
     public function confirmDelivery(Request $request, $id)
    {
    $procRequest = ProcurementRequest::findOrFail($id);

    if ($procRequest->status !== 'delivery_accepted') {
        return redirect()->back()->with('error', 'Cannot confirm delivery at this stage.');
    }

    // Update inventory
    $inventory = \App\Models\Inventory::firstOrCreate(['product_id' => $procRequest->product_id]);
    $inventory->quantity += $procRequest->quantity;
    $inventory->save();

    // Set status to delivered
    $procRequest->status = 'delivered';
    $procRequest->save();

    // Optionally, trigger production if there are pending orders for this product
    // (You can implement logic here to check for pending orders and call triggerProduction)

    return redirect()->back()->with('success', 'Procurement marked as delivered and stock updated.');
    }
}