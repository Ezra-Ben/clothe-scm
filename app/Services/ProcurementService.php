<?php
namespace App\Services;

use App\Models\Supplier;
use App\Models\ProcurementRequest;
use App\Models\ProcurementDelivery;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ProcurementService
{
    public function createProcurementRequest($productId, $quantity, $orderId = null)
    {  
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be positive.');
        }
        $supplier = $this->getNextSupplier();
        return ProcurementRequest::create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'requested_quantity' => $quantity,
            'status' => 'pending',
            'supplier_id' => $supplier->id,
            'order_id' => $orderId,
        ]);
    }

    public function getNextSupplier()
    {
        return Supplier::active()->orderBy('last_supplied_at')->first();
    }

    public function logDelivery($procurementRequestId, $deliveredQuantity)
    {
        $request = ProcurementRequest::findOrFail($procurementRequestId);
        $delivery = ProcurementDelivery::create([
            'procurement_request_id' => $request->id,
            'supplier_id' => $request->supplier_id,
            'delivered_quantity' => $deliveredQuantity,
            'delivered_at' => now(),
        ]);
        // Update supplier's last_supplied_at
        $request->supplier->update(['last_supplied_at' => now()]);
        // Update inventory
        $inventory = Inventory::firstOrCreate(['product_id' => $request->product_id]);
        $inventory->quantity += $deliveredQuantity;
        $inventory->save();
        // Update procurement request status
        $request->status = 'delivered';
        $request->save();

        // --- AUTOMATION: Trigger production for pending orders ---
        $pendingOrders = Order::where('product_id', $request->product_id)
            ->whereIn('status', ['production', 'pending'])
            ->orderBy('created_at')
            ->get();

        foreach ($pendingOrders as $order) {
            // Check if enough raw materials for this order
            if (app(\App\Services\InventoryService::class)->canProduce($order->product_id, $order->quantity) === []) {
                app(\App\Services\InventoryService::class)->triggerProduction($order->product_id, $order->quantity, $order->id);
                // Optionally update order status if needed
            }
        }

        return $delivery;
    }
}