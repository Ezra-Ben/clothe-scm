<?php

namespace App\Services;

use App\Models\ProcurementRequest;
use App\Models\InboundShipment;

class InboundShipmentService
{
    /**
     * Create an inbound shipment for a given procurement request.
     */
    public function createForProcurementRequest(ProcurementRequest $request): InboundShipment
    {
        return InboundShipment::create([
            'procurement_request_id'   => $request->id,
            'supplier_id'              => $request->supplier_id,
            'carrier_id'               => null, 
            'tracking_number'          => 'OB-' . now()->timestamp, 
            'status'                   => 'pending',
            'estimated_delivery_date'  => now()->addDays(3), 
            'actual_delivery_date'     => null,
        ]);
    }
}
