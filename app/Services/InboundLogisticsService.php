<?php
namespace App\Services;

use App\Models\{
    SupplierOrder, 
    InboundShipment, 
    ReceivingReport, 
    User,
    InboundShipmentStatusHistory
};
use Illuminate\Support\Facades\DB;

class InboundLogisticsService
{
    
public function createInboundShipment(SupplierOrder $order, array $data): InboundShipment
{
    return InboundShipment::create([
        'supplier_order_id' => $order->id, // This was missing
        'carrier_id' => $data['carrier_id'],
        'tracking_number' => $data['tracking_number'],
        'status' => $data['status'],
        'estimated_arrival' => $data['estimated_arrival'],
        'actual_arrival' => $data['actual_arrival'] ?? null,
    ]);
}

public function updateShipmentStatus(
    InboundShipment $shipment,
    string $newStatus,
    ?User $changedBy = null
): InboundShipment {
    $shipment->update(['status' => $newStatus]);

    InboundShipmentStatusHistory::create([
        'inbound_shipment_id' => $shipment->id,
        'status' => $newStatus,
        'changed_at' => now(),
        'changed_by' => $changedBy?->id,
    ]);

    return $shipment;
}


    public function receiveShipment(
        InboundShipment $shipment, 
        User $receivedBy,
        array $details
    ): ReceivingReport {
        return DB::transaction(function () use ($shipment, $receivedBy, $details) {
            $shipment->update([
                'status' => 'received',
                'actual_arrival' => now()
            ]);

            return ReceivingReport::create([
                'shipment_id' => $shipment->id,
                'received_by' => $receivedBy->id,
                'received_at' => now(),
                'condition' => $details['condition'],
                'discrepancy_notes' => $details['discrepancy_notes']
            ]);
        });
    }
}