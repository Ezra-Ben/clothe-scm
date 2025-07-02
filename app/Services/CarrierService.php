<?php
namespace App\Services;

use App\Models\Carrier;

class CarrierService
{
    public function getOptimalCarrier(float $weight, string $destination): ?Carrier
    {
        return Carrier::where('max_weight_kg', '>=', $weight)
            ->whereJsonContains('service_areas', $destination)
            ->orderBy('base_rate_usd')
            ->first();
    }
     public function deleteCarrier(Carrier $carrier): bool
    {
        return $carrier->delete();
    }
}