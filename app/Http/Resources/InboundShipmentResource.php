<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InboundShipmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'supplier' => $this->supplier->name,
            'carrier' => $this->carrier->name,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'eta' => $this->estimated_arrival->toIso8601String(),
            'received_at' => optional($this->actual_arrival)->toIso8601String()
        ];
    }
}