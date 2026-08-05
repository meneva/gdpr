<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataBreachRequest extends FormRequest
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
            'discovered_at' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
