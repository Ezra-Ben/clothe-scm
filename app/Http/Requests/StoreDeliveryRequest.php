<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeliveryRequest extends FormRequest
{
    public function rules()
    {
        return [
              'order_id' => 'required|exists:orders,id',
            'carrier_id' => 'required|exists:carriers,id',
            'tracking_number' => 'required|string|unique:deliveries,tracking_number|max:255',
            'service_level' => [
                'required',
                Rule::in(['standard', 'express', 'overnight']),
            ],
            'status' => [
                'required',
                Rule::in(['pending', 'processing', 'dispatched', 'in_transit', 'out_for_delivery', 'delivered', 'failed']),
            ],
            'estimated_delivery' => 'nullable|date',
            'notes' => 'nullable|string',
            'route' => 'nullable|array', 
            
        ];
    }
}
