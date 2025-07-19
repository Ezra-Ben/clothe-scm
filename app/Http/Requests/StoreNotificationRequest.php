<?php
// app/Http/Requests/StoreNotificationRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'message' => 'required|string',
            'meta' => 'nullable|array',
        ];
    }
}
