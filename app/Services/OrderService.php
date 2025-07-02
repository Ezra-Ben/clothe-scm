<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Services\InventoryService; // Import InventoryService
use App\Services\ProductionService; // Import ProductionService (if it creates production orders)
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $inventoryService;
    protected $productionService; // Inject ProductionService if it handles ProductionOrder creation

    public function __construct(InventoryService $inventoryService, ProductionService $productionService)
    {
        $this->inventoryService = $inventoryService;
        $this->productionService = $productionService;
    }

    /**
     * Places a new customer order, handles stock fulfillment and MTO assignment.
     *
     * @param string $customerName
     * @param array $items // Array of ['product_id' => int, 'quantity' => int]
     * @return Order
     * @throws \Exception
     */
    public function placeOrder(string $customerName, array $items): Order
    {
        if (empty($items)) {
            throw new \Exception('Order must contain at least one item.');
        }

        return DB::transaction(function () use ($customerName, $items) {
            $order = Order::create([
                'customer_name' => $customerName,
                'status' => 'pending',
            ]);

            foreach ($items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $requestedQuantity = $itemData['quantity'];

                $fulfilledFromStock = 0;
                $makeToOrderQuantity = 0;

                // Check and deduct from finished goods inventory
                if ($this->inventoryService->checkFinishedGoodsAvailability($product, $requestedQuantity)) {
                    $fulfilledFromStock = $requestedQuantity;
                    $this->inventoryService->deductFinishedGoods($product, $requestedQuantity);
                } else {
                    $availableStock = FinishedGoodsInventory::where('product_id', $product->id)->first()->quantity ?? 0;
                    if ($availableStock > 0) {
                        $fulfilledFromStock = $availableStock;
                        $this->inventoryService->deductFinishedGoods($product, $availableStock);
                    }
                    $makeToOrderQuantity = $requestedQuantity - $fulfilledFromStock;
                }

                $orderItem = $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $requestedQuantity,
                    'fulfilled_from_stock' => ($fulfilledFromStock > 0), // True if any part came from stock
                    'status' => ($makeToOrderQuantity > 0) ? 'MTO' : 'fulfilled', // Initial status
                ]);

                // If make-to-order is required, create a production order
                if ($makeToOrderQuantity > 0) {
                    $bom = \App\Models\Bom::where('product_id', $product->id)->first();
                    if (!$bom) {
                        throw new \Exception("Product '{$product->name}' requires production but has no BOM defined.");
                    }
                    // Delegate production order creation to ProductionService
                    $productionOrder = $this->productionService->createProductionOrderForOrderItem(
                        $product, $makeToOrderQuantity, $orderItem
                    );
                    $orderItem->production_order_id = $productionOrder->id;
                    $orderItem->status = 'MTO'; // Explicitly set status to MTO
                    $orderItem->save();
                }
            }

            // Update overall order status based on item statuses
            $order->status = $order->orderItems->contains('status', 'MTO') ? 'pending' : 'fulfilled';
            $order->save();

            return $order;
        });
    }

    /**
     * Updates the status of an existing order.
     *
     * @param Order $order
     * @param string $newStatus
     * @return void
     * @throws \Exception
     */
    public function updateOrderStatus(Order $order, string $newStatus): void
    {
        // Add validation for status transitions if necessary
        $validStatuses = ['pending', 'fulfilled', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            throw new \Exception('Invalid order status provided.');
        }

        $order->status = $newStatus;
        $order->save();

        // Additional logic could go here, e.g., if cancelled, return stock
    }

    // add methods for cancelling orders, processing returns, etc.
}

