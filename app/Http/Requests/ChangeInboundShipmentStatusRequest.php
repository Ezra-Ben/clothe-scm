<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeInboundShipmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add your auth logic if needed
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['processing', 'in_transit', 'arrived', 'received']),
            ],
        ];
    }
}
