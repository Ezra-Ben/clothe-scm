<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarrierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
        'name' => 'required|string|max:255',
        'contact_phone' => 'required|string|max:20',
        'code' => 'required|string|max:50|unique:carriers,code,' . $this->route('carrier')->id,
        'supported_service_levels' => 'required|string', // you'll parse this string later
        'service_areas' => 'required|string',             // same here
        'base_rate_usd' => 'required|numeric|min:0',
        'max_weight_kg' => 'required|numeric|min:0',
        'tracking_url_template' => 'nullable|url',
        'is_active' => 'nullable',
    ];
    }
}
