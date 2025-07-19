<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pod;
use App\Models\OutboundShipment;
use App\Models\InboundShipment;

class PodSeeder extends Seeder
{
    public function run()
    {
        $outbound = OutboundShipment::where('status', 'delivered')->get();
        $inbound = InboundShipment::where('status', 'delivered')->get();

        foreach ($outbound as $shipment) {
            Pod::create([
                'shipment_id' => $shipment->id,
                'shipment_type' => OutboundShipment::class,
                'delivered_by' => 'James K.',
                'received_by' => 'Sarah M.',
                'received_at' => now(),
                'delivery_notes' => 'Delivered safely.',
                'recipient_name' => 'Sarah M.',
                'condition' => 'Excellent',
                'discrepancies' => 'None',
            ]);
        }

        foreach ($inbound as $shipment) {
            Pod::create([
                'shipment_id' => $shipment->id,
                'shipment_type' => InboundShipment::class,
                'delivered_by' => 'Logistics Team',
                'received_by' => 'Warehouse A',
                'received_at' => now(),
                'delivery_notes' => 'All items accounted for.',
                'recipient_name' => 'John B.',
                'condition' => 'Good',
                'discrepancies' => '1 item delayed',
            ]);
        }
    }
}