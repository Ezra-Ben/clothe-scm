<?php

namespace App\Services;

use App\Models\BOM;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\RawMaterial;
use App\Models\ProductionOrder;
use App\Models\OrderFulfillment;

class ProductionService
{
    protected $inventoryService;

    public function __construct(\App\Services\InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Trigger production for a customer order:
     * tries to produce immediately or plans if raw materials missing.
     */
    public function checkAndProduce(Order $order)
    {
        $productionOrders = [];
        $missingPerProductionOrder = [];

        foreach ($order->items as $item) {
            $result = $this->processProduction(
                $item->product_id,
                $item->quantity,
                $order->id
            );

            $productionOrders[] = $result['production_order_id'];
            $missingPerProductionOrder[$result['production_order_id']] = $result['missing'];
        }

        return [
            'production_order_ids' => $productionOrders,
            'missingPerProductionOrder' => $missingPerProductionOrder,
        ];
    }

    /**
     * Manual admin: Create a production order for stock only.
     */
    public function createProductionOrderForStock($productId, $quantity)
    {
        $result = $this->processProduction($productId, $quantity, null);

        return ProductionOrder::find($result['production_order_id']);
    }

    /**
     * Core helper: handles BOM, checks stock, consumes or plans.
     */
    protected function processProduction($productId, $quantity, $orderId = null)
    {
        $bom = BOM::where('product_id', $productId)->first();

        $requiredRawMaterials = [];

        if ($bom) {
            foreach ($bom->items as $bomItem) {
                $requiredRawMaterials[$bomItem->raw_material_id] =
                    ($requiredRawMaterials[$bomItem->raw_material_id] ?? 0)
                    + ($bomItem->quantity * $quantity);
            }
        }

        $canProduce = $this->inventoryService->hasEnoughRawMaterials($requiredRawMaterials);
        $status = $canProduce ? 'in_production' : 'planned';

        $productionOrder = ProductionOrder::create([
            'order_id'   => $orderId,
            'product_id' => $productId,
            'quantity'   => $quantity,
            'status'     => $status,
        ]);

        $missing = [];

        if ($canProduce) {
            $this->inventoryService->consumeRawMaterials($requiredRawMaterials);
        } else {
            foreach ($requiredRawMaterials as $rmId => $needed) {
                $stock = RawMaterial::where('id', $rmId)->value('quantity_on_hand') ?? 0;
                if ($stock < $needed) {
                    $missing[$rmId] = $needed - $stock;
                }
            }


            if (!empty($missing)) {
                app(\App\Services\ProcurementService::class)
                    ->requestRawMaterials($missing, $orderId, $productionOrder->id);
            }
        }

        return [
            'production_order_id' => $productionOrder->id,
            'missing' => $missing,
        ];
    }

    /**
     * Try to start any 'planned' orders if stock is now sufficient.
     */
    public function tryStartPlannedProduction($rawMaterialId = null)
    {
        $plannedOrders = ProductionOrder::where('status', 'planned')->get();

        foreach ($plannedOrders as $productionOrder) {
            $needed = $this->getRawMaterialsForProductionOrder($productionOrder->id);

            if ($rawMaterialId && !array_key_exists($rawMaterialId, $needed)) {
                continue;
            }

            if ($this->inventoryService->hasEnoughRawMaterials($needed)) {
                $this->inventoryService->consumeRawMaterials($needed);
                $productionOrder->status = 'in_production';
                $productionOrder->save();
                
                // Only complete production if it was successfully started
                $this->completeProduction($productionOrder->id);

                if ($productionOrder->order_id) {
                    OrderFulfillment::updateOrCreate(
                        ['order_id' => $productionOrder->order_id],
                        [
                            'status' => 'in_production',
                            'payment_date' => now(),
                            'updated_by' => auth()->id() ?? 0,
                            'updated_by_role' => auth()->user()->role->name ?? 'system',
                        ]
                    );

                    dispatch(new \App\Jobs\MarkProductionCompleted($productionOrder->order_id))->delay(now()->addMinutes(2));
                    dispatch(new \App\Jobs\MarkReadyForShipping($productionOrder->order_id))->delay(now()->addMinutes(4));
                }
            }
        }
    }

    public function getRawMaterialsForProductionOrder($productionOrderId)
    {
        $productionOrder = ProductionOrder::findOrFail($productionOrderId);
        $bom = BOM::where('product_id', $productionOrder->product_id)->first();

        $rawMaterials = [];

        if ($bom) {
            foreach ($bom->items as $bomItem) {
                $required = $bomItem->quantity * $productionOrder->quantity;
                $rawMaterials[$bomItem->raw_material_id] = $required;
            }
        }

        return $rawMaterials;
    }

    /**
     * Mark a production order as completed → finished goods into stock.
     */
    public function completeProduction($productionOrderId)
    {
        $productionOrder = ProductionOrder::findOrFail($productionOrderId);

        if ($productionOrder->status !== 'in_production') {
        throw new \Exception("Cannot complete production that has not been started!");
        }

        $productionOrder->status = 'completed';
        $productionOrder->save();

        $inventory = Inventory::firstOrCreate(['product_id' => $productionOrder->product_id]);
        $inventory->quantity_on_hand += $productionOrder->quantity;

        if ($productionOrder->order_id) {
        // Reserve the produced units for this order
        $inventory->quantity_reserved += $productionOrder->quantity;
        $inventory->quantity_on_hand -= $productionOrder->quantity;
        } 

        $inventory->save();
    }
}
