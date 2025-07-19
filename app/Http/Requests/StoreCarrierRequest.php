<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarrierRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Or add logic if needed
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:carriers',
            'contact_phone' => 'required|string|max:20',
            'supported_service_levels' => 'required|string',
            'service_areas' => 'required|string',
            'base_rate_usd' => 'required|numeric|min:0',
            'max_weight_kg' => 'required|numeric|min:0',
            'tracking_url_template' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
