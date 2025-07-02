<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInboundShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Set to true unless using policies
    }

    public function rules(): array
    {
        return [
            'carrier_id' => 'required|exists:carriers,id',
            'tracking_number' => 'required|string|max:255',
            'estimated_arrival' => 'required|date',
            'status' => 'required|in:processing,in_transit,arrived,received',
        ];
    }
}
