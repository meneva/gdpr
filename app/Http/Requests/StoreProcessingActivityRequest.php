<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessingActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'purpose' => ['nullable', 'string'],
            'legal_basis' => ['nullable', 'string', 'max:255'],
            'data_categories' => ['nullable', 'string'],
            'retention_period' => ['nullable', 'string', 'max:255'],
            'third_parties_involved' => ['nullable', 'string'],
            'owner_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
