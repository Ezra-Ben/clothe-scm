<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // You can add permission logic here later if needed
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'task_id' => 'required|exists:tasks,id',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee to assign.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'task_id.required' => 'A task must be selected.',
            'task_id.exists' => 'The selected task does not exist.',
        ];
    }
}
