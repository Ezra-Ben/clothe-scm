<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    
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
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'average_duration_minutes' => 'required|integer|min:1',
            'positions' => 'required|array|min:1',
             'positions.*.position_id' => 'required|exists:positions,id',
        'positions.*.required_count' => 'required|integer|min:1',
        ];
    }
}
