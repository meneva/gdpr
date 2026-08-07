<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDpiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'risk_level' => ['required', 'in:low,medium,high'],
            'due_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
