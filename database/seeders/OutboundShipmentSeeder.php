<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OutboundShipment;
use App\Models\Order;
use App\Models\Carrier;

class OutboundShipmentSeeder extends Seeder
{
    public function run()
    {
        $carrierIds = Carrier::pluck('id');
        $orders = Order::inRandomOrder()->take(2)->get();

        foreach ($orders as $index => $order) {
            OutboundShipment::create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'carrier_id' => $carrierIds[$index % 2],
                'tracking_number' => 'OUT' . rand(10000, 99999),
                'status' => $index == 0 ? 'pending' : 'delivered',
                'estimated_delivery_date' => now()->addDays(3),
                'actual_delivery_date' => $index == 0 ? null : now(),
            ]);
        }
    }
}
