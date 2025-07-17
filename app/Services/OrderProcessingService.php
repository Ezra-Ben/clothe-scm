<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderFulfillment;
use App\Services\InventoryService;
use App\Services\ProductionService;
use App\Services\ProcurementService;
use App\Services\OutboundShipmentService;

class OrderProcessingService
{
    protected $outbound;
    protected $inventory;
    protected $production;
    protected $procurement;

    public function __construct(
        InventoryService $inventory,
        ProductionService $production,
        ProcurementService $procurement,
        OutboundShipmentService $outbound
    ) {
        $this->inventory = $inventory;
        $this->production = $production;
        $this->procurement = $procurement;
        $this->outbound = $outbound;
    }

    public function processPaidOrder(Order $order)
    {
    $hasStock = $this->inventory->hasEnoughStock($order);

    $fulfillmentStatus = '';

    if ($hasStock) {
        $this->inventory->reserveStock($order);
        $fulfillmentStatus = 'ready_for_shipping';

    } else {
            $result = $this->production->checkAndProduce($order);

            $missingPerProductionOrder = $result['missingPerProductionOrder'];
            $productionOrderIds = $result['production_order_ids'];
            $missingMaterials = false;
            
            foreach ($productionOrderIds as $productionOrderId) {
                $missingRawMaterials = $missingPerProductionOrder[$productionOrderId] ?? [];

                if (empty($missingRawMaterials)) {
                    // Already in production — so we just mark it as completed
                    $this->production->completeProduction($productionOrderId);
                } else {
                    $missingMaterials = true;
                }
            }

        
            if ($missingMaterials) {
                $fulfillmentStatus = 'production_planned'; 
            } else {
                $fulfillmentStatus = 'in_production'; 
            }
    }

    OrderFulfillment::updateOrCreate(
        ['order_id' => $order->id],
        [
            'status' => $fulfillmentStatus,
            'payment_date' => now(),
            'updated_by' => auth()->id(),
            'updated_by_role' => auth()->user()->role->name ?? 'system',
        ]
    );

    //If production was triggered, fire the staged update jobs!
    if ($fulfillmentStatus === 'in_production') {
        dispatch(new \App\Jobs\MarkProductionCompleted($order->id))->delay(now()->addMinutes(2));
        dispatch(new \App\Jobs\MarkReadyForShipping($order->id))->delay(now()->addMinutes(4));
    }

    if ($fulfillmentStatus === 'ready_for_shipping') {
        $shipment = $this->outbound->createForOrder($order);

        $logisticsManager = User::all()->first(function ($user) {
            return $user->hasRole('logistics_manager');
        });

        if ($logisticsManager) {
            $logisticsManager->notify(new \App\Notifications\OutboundShipmentCreatedNotification($shipment));  
        }

    }

}

}
