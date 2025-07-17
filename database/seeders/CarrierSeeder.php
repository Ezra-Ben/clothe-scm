<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrier;
use App\Models\User;

class CarrierSeeder extends Seeder
{
    public function run()
    {
        $users = User::whereIn('email', ['carrier1@example.com', 'carrier2@example.com'])->get();

        Carrier::create([
            'user_id' => $users[0]->id,
            'status' => 'active',
            'contact_phone' => '0701000001',
            'vehicle_type' => 'Truck',
            'license_plate' => 'UAX123A',
            'service_areas' => json_encode(['Central', 'Northern']),
            'max_weight_kg' => 5000,
            'customer_rating' => 0,
        ]);

        Carrier::create([
            'user_id' => $users[1]->id,
            'status' => 'active',
            'contact_phone' => '0701000002',
            'vehicle_type' => 'Van',
            'license_plate' => 'UBF456B',
            'service_areas' => json_encode(['Western', 'Eastern']),
            'max_weight_kg' => 2000,
            'customer_rating' => 0,
        ]);
    }
}
