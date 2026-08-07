<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDpiaRequest extends FormRequest
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
            'status' => ['required', 'in:draft,in_review,approved,rejected'],
            'due_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
