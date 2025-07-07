<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductionBatchRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $batchId = $this->route('production_batch')?->id;
        
        return [
            'batch_number' => [
                'required', 
                'string', 
                'max:50',
                'regex:/^[A-Z0-9\-_]+$/',
                Rule::unique('production_batches')->ignore($batchId)
            ],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999999'],
            'status' => ['required', 'string', 'in:pending,in_progress,completed,cancelled'],
            'started_at' => ['nullable', 'date', 'before_or_equal:now'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'batch_number.required' => 'Batch number is required.',
            'batch_number.regex' => 'Batch number can only contain uppercase letters, numbers, hyphens, and underscores.',
            'batch_number.unique' => 'This batch number is already in use.',
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'The selected product is invalid.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 999,999.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: pending, in progress, completed, or cancelled.',
            'started_at.date' => 'Start date must be a valid date.',
            'started_at.before_or_equal' => 'Start date cannot be in the future.',
            'completed_at.date' => 'Completion date must be a valid date.',
            'completed_at.after_or_equal' => 'Completion date must be after or equal to start date.',
        ];
    }

    /**
     * Get custom attributes for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'batch_number' => 'batch number',
            'product_id' => 'product',
            'quantity' => 'quantity',
            'status' => 'status',
            'started_at' => 'start date',
            'completed_at' => 'completion date',
        ];
    }
} 