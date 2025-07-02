<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInboundShipmentCarrierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
          return $this->user()->role === 'carrier';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
             return [
            'status' => 'required|string|in:processing,in_transit,arrived,received',
            'estimated_arrival' => 'nullable|date',
        ];
    
    }
}
