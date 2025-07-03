<?php

namespace App\Services;

use App\Models\ProductionBatch;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Support\Str;//for batch code generation
use App\Models\ProductionOrder;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionService
{
    public function startBatchFromOrder($orderId, $productId)
    {
        $inventory = InventoryItem::where('id', $productId)->first();

        if (!$inventory || $inventory->stock_quantity <= 0) {
            return ['error' => 'Insufficient stock for production.'];
        }

        // Optional: Deduct one unit from inventory here or later after production completes
        // $inventory->decrement('stock_quantity');

        return ProductionBatch::create([
            'order_id'     => $orderId,
            'product_id'   => $productId,
            'batch_code'   => 'AUTO-' . strtoupper(Str::random(6)),
            'status'       => 'pending',
            'scheduled_at' => now()->addDay(),
        ]);
    }
    public function completeBatch($id){
    Log::info("completeBatch method called for ID: ".$id);
    return true;
    }

    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Completes a production order and updates related inventories and order items.
     * Now uses InventoryService to receive finished goods.
     *
     * @param ProductionOrder $productionOrder
     * @return bool
     * @throws \Exception
     */
    public function completeProductionOrder(ProductionOrder $productionOrder): bool
    {
        if ($productionOrder->status !== 'In Progress') {
            throw new \Exception('Only in-progress orders can be completed.');
        }

        return DB::transaction(function () use ($productionOrder) {
            // Update production order status
            $productionOrder->status = 'Completed';
            $productionOrder->completed_at = now();
            $productionOrder->save();

            // Receive finished goods to inventory using InventoryService
            $this->inventoryService->receiveFinishedGoods($productionOrder->product, $productionOrder->quantity);

            // Mark linked order_items as fulfilled
            foreach ($productionOrder->orderItems as $orderItem) {
                if ($orderItem->status === 'MTO') { // Only mark MTO items as fulfilled by this production
                    $orderItem->status = 'fulfilled'; // Changed from 'Fulfilled' for consistency with order item status
                    $orderItem->save();
                }
            }
            return true;
        });
    }

    /**
     * Creates a new production order for a given product and quantity, linked to an order item.
     * This method would be called by OrderService for MTO items.
     *
     * @param Product $product
     * @param int $quantity
     * @param OrderItem $orderItem
     * @param bool $urgent
     * @return ProductionOrder
     * @throws \Exception If no BOM is found or raw materials are insufficient.
     */
    public function createProductionOrderForOrderItem(Product $product, int $quantity, OrderItem $orderItem, bool $urgent = false): ProductionOrder
    {
        $bom = \App\Models\Bom::where('product_id', $product->id)->first();
        if (!$bom) {
            throw new \Exception("Cannot create production order: Product '{$product->name}' has no BOM defined.");
        }

        // Optional: Check raw material availability here before creating the order
        // if (!$this->inventoryService->checkRawMaterialAvailability($bom, $quantity)) {
        //     throw new \Exception("Insufficient raw materials for production order of '{$product->name}'.");
        // }

        return DB::transaction(function () use ($product, $quantity, $orderItem, $bom, $urgent) {
            $productionOrder = ProductionOrder::create([
                'batch_code' => 'PROD-' . Str::upper(Str::random(8)),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'status' => 'Pending',
                'urgent' => $urgent,
                'packaging_status' => 'Unassigned',
                'scheduled_at' => now()->addDays(rand(1, 7)), // Example scheduling
                'bom_id' => $bom->id,
            ]);

            // Link the order item to this production order
            $orderItem->production_order_id = $productionOrder->id;
            $orderItem->save();

            return $productionOrder;
        });
    }

    /**
     * Initiates a production order, deducting raw materials.
     * This could be called when the status moves from 'Pending' to 'In Progress'.
     *
     * @param ProductionOrder $productionOrder
     * @return void
     * @throws \Exception
     */
    public function startProductionOrder(ProductionOrder $productionOrder): void
    {
        if ($productionOrder->status !== 'Pending') {
            throw new \Exception('Only pending production orders can be started.');
        }

        DB::transaction(function () use ($productionOrder) {
            // Deduct raw materials using InventoryService
            $this->inventoryService->deductRawMaterials($productionOrder->bom, $productionOrder->quantity);

            $productionOrder->status = 'In Progress';
            $productionOrder->save();
        });
    }
}

           



