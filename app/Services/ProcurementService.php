<?php

namespace App\Services;

use App\Models\ProcurementRequest;
use App\Notifications\NewProcurementRequestNotification;

class ProcurementService
{
    /**
     * Create procurement requests for missing raw materials.
     * 
     * @param array $missingRawMaterials  [raw_material_id => quantity_needed]
     * @param int|null $orderId           Related customer order ID (optional)
     * @param int|null $productionOrderId Related production order ID (optional)
     * 
     * @return void
     */
    public function requestRawMaterials(array $missingRawMaterials, ?int $orderId = null, ?int $productionOrderId = null)
{
    foreach ($missingRawMaterials as $rawMaterialId => $quantity) {
        // Check if a procurement request already exists for this raw material
        $existingRequest = ProcurementRequest::where('raw_material_id', $rawMaterialId)
            ->where('status', 'pending')
            ->when($orderId, fn($q) => $q->where('order_id', $orderId))
            ->when($productionOrderId, fn($q) => $q->where('production_order_id', $productionOrderId))
            ->first();

        if ($existingRequest) {
            // If it exists, update the quantity to ensure no duplication
            $existingRequest->quantity = max($existingRequest->quantity, $quantity);
            $existingRequest->save();
        } else {
            //Load the raw material
            $rawMaterial = \App\Models\RawMaterial::findOrFail($rawMaterialId);
            $supplierId = $rawMaterial->supplier_id;

            //Assign the created model
            $procurementRequest = ProcurementRequest::create([
                'raw_material_id' => $rawMaterialId,
                'quantity' => $quantity,
                'status' => 'pending',
                'order_id' => $orderId,
                'production_order_id' => $productionOrderId,
                'supplier_id' => $supplierId,
            ]);

            
            // Load the supplier via related user
            $supplier = $procurementRequest->supplier->vendor->user;

            // Notify user
            $supplier->notify(new NewProcurementRequestNotification($procurementRequest));
            
            }
        }
    }

}
