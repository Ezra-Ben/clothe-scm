<?php
namespace App\Services;

use App\Models\{Delivery, Order, Carrier,DeliveryStatusHistory};
use Illuminate\Support\Str;

class DeliveryService
{
    public function initiateDelivery(Order $order, Carrier $carrier, string $serviceLevel): Delivery
    {
        return Delivery::create([
            'order_id' => $order->id,
            'carrier_id' => $carrier->id,
            'tracking_number' => $this->generateTrackingNumber($carrier),
            'service_level' => $serviceLevel,
            'status' => 'pending',
            'estimated_delivery' => $this->calculateDeliveryDate($serviceLevel),
            'route' => [
                'stops' => [
                    $carrier->hub_location,
                    $order->shipping_address
                ],
                'vehicle_type' => $this->determineVehicleType($order->package_weight_kg)
            ]
        ]);
    }

    protected function generateTrackingNumber(Carrier $carrier): string
    {
        return $carrier->code . '-' . Str::upper(Str::random(10));
    }

    protected function determineVehicleType(float $packageWeightKg): string
    {
        if ($packageWeightKg <= 5) {
            return 'bike';
        } elseif ($packageWeightKg <= 20) {
            return 'van';
        } else {
            return 'truck';
        }
    }

    protected function calculateDeliveryDate(string $serviceLevel): \DateTimeImmutable
{
    return match($serviceLevel) {
        'overnight' => now()->addDay()->toDateTimeImmutable(),
        'express' => now()->addDays(2)->toDateTimeImmutable(),
        default => now()->addWeekdays(5)->toDateTimeImmutable()
    };
}

public function updateDeliveryStatus(Delivery $delivery, string $status, ?User $changedBy = null)
{
    $delivery->update(['status' => $status]);

    DeliveryStatusHistory::create([
        'delivery_id' => $delivery->id,
        'status' => $status,
        'changed_at' => now(),
        'changed_by' => $changedBy?->id,
    ]);

    return $delivery;
}

}