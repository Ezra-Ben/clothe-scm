<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OutboundShipment;

class OutboundShipmentService
{
    public function createForOrder(Order $order): OutboundShipment
    {
        return OutboundShipment::firstOrCreate(
            ['order_id' => $order->id],
            [
            'customer_id' => $order->customer_id,
            'status' => 'pending',
            'tracking_number' => 'OB-' . now()->timestamp,
            'estimated_delivery_date' => now()->addDays(3),
            ]
        );
    }
}
