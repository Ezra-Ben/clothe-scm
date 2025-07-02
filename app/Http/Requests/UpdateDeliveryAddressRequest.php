<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // You can put ownership logic here, or just return true for now
        return true;
    }

    public function rules(): array
    {
        return [
            'new_address' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_address.required' => 'Please enter the new delivery address.',
            'new_address.max' => 'The address is too long.',
        ];
    }
}
