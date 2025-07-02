<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveShipmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'condition' => ['required', 'string', 'max:255'],
            'discrepancy_notes' => ['nullable', 'string'],
            'actual_arrival' => ['required', 'date', 'before_or_equal:now'], 
        ];
    }
}
