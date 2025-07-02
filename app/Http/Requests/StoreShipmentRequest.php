<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShipmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'supplier_order_id' => ['required', 'exists:supplier_orders,id'],
            'carrier_id'        => ['required', 'exists:carriers,id'],
            'tracking_number'   => ['required', 'string', 'max:255', 'unique:inbound_shipments,tracking_number'],
            'status'            => ['required', 'in:processing,in_transit,arrived,received'],
            'estimated_arrival' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:now'],
            'actual_arrival'    => ['nullable', 'date', 'after_or_equal:estimated_arrival'],
        ];
    }
}