<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDataBreachRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:assessing,notified,resolved'],
            'description' => ['nullable', 'string'],
            'ico_notified_at' => ['nullable', 'date'],
            'resolved_at' => ['nullable', 'date'],
        ];
    }
}
