<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You already gate via middleware, this can be true
        return true;
    }

    public function rules(): array
    {
        return [
            'contract_number' => 'required|string|max:191|unique:contracts,contract_number',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'status'          => 'required|in:active,expired,pending',
            'terms'           => 'required|string',
            'payment_terms'   => 'nullable|string|max:191',
            'renewal_date'    => 'nullable|date|after_or_equal:end_date',
            'notes'           => 'nullable|string',
        ];
    }
}
