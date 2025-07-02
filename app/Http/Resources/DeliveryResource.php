<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
   public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'carrier' => $this->carrier->name,
            'service_level' => $this->service_level,
            'estimated_delivery' => $this->estimated_delivery->format('Y-m-d H:i')
        ];
    }
}
