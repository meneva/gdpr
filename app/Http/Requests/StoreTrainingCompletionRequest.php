<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingCompletionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'staff_name' => ['required', 'string', 'max:255'],
            'staff_email' => ['nullable', 'email', 'max:255'],
            'due_at' => ['nullable', 'date'],
        ];
    }
}
