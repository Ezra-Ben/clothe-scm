<?php

namespace App\Services;

use App\Models\Carrier;

class CarrierService
{
    public function filterCarriers($serviceArea, $vehicleType = null, $requiredQuantity = null)
    {
        return Carrier::where('status', 'free')
            ->when($serviceArea, function ($query) use ($serviceArea) {
                $query->where('service_areas', 'LIKE', "%{$serviceArea}%");
            })
            ->when($vehicleType, function ($query) use ($vehicleType) {
                $query->where('vehicle_type', $vehicleType);
            })
            ->when($requiredQuantity >= 1000, function ($query) {
                $query->where('max_weight_kg', '>=', 1000);
            })
            ->get();
    }
}
