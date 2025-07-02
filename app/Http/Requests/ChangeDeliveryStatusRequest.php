<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeDeliveryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'pending', 
                    'processing', 
                    'dispatched', 
                    'in_transit', 
                    'out_for_delivery', 
                    'delivered', 
                    'failed'
                ]),
            ],
        ];
    }
}
