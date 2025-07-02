<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'carrier_id' => 'nullable|exists:carriers,id',
            'tracking_number' => 'required|string|max:255|unique:deliveries,tracking_number,' . $this->delivery->id,
            'status' => 'required|in:pending,processing,dispatched,in_transit,out_for_delivery,delivered,failed',
            'service_level' => 'required|string',
            'estimated_delivery' => 'nullable|date',
            'actual_delivery' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}
