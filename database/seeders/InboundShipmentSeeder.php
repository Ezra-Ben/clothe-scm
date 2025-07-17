<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InboundShipment;
use App\Models\ProcurementRequest;
use App\Models\Supplier;
use App\Models\Carrier;

class InboundShipmentSeeder extends Seeder
{
    public function run()
    {
        $carrierIds = Carrier::pluck('id');
        $requests = ProcurementRequest::inRandomOrder()->take(2)->get();

        foreach ($requests as $index => $req) {
            InboundShipment::create([
                'procurement_request_id' => $req->id,
                'supplier_id' => $req->supplier_id,
                'carrier_id' => $carrierIds[$index % 2],
                'tracking_number' => 'IN' . rand(10000, 99999),
                'status' => $index == 0 ? 'dispatched' : 'delivered',
                'estimated_delivery_date' => now()->addDays(2),
                'actual_delivery_date' => $index == 0 ? null : now(),
            ]);
        }
    }
}
