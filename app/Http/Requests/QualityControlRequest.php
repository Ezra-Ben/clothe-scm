<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QualityControlRequest extends FormRequest
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
        return [
            'production_batch_id' => ['required', 'exists:production_batches,id'],
            'tester_id' => ['required', 'exists:users,id'],
            'defects_found' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', 'in:pending,passed,failed,retest'],
            'tested_at' => ['nullable', 'date', 'before_or_equal:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'production_batch_id.required' => 'Please select a production batch.',
            'production_batch_id.exists' => 'The selected production batch is invalid.',
            'tester_id.required' => 'Please select a tester.',
            'tester_id.exists' => 'The selected tester is invalid.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: pending, passed, failed, or retest.',
            'tested_at.date' => 'Test date must be a valid date.',
            'tested_at.before_or_equal' => 'Test date cannot be in the future.',
        ];
    }

    /**
     * Get custom attributes for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'production_batch_id' => 'production batch',
            'tester_id' => 'tester',
            'defects_found' => 'defects found',
            'status' => 'status',
            'tested_at' => 'test date',
        ];
    }
} 