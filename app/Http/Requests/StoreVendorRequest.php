<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'product_bulk'          => 'required|string|max:255',
            'registration_number'   => 'required|string|max:100',
            'contact'               => 'required|string|max:255',
            'previous_clients'      => 'nullable|array', 
            'previous_clients.*'    => 'nullable|array',
            'transaction_history'   => 'nullable|array',
            'industry_rating'       => 'nullable|array',
            'product_category'      => 'required|string', 
            'business_license_url'  => 'required|url',
        ];
    }
}