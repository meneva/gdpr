<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'dpa_on_file' => ['nullable', 'boolean'],
            'risk_level' => ['required', 'in:low,medium,high'],
            'last_reviewed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Checkbox inputs don't send anything when unchecked — normalize
        // to a real boolean so it isn't left null in the database.
        $this->merge([
            'dpa_on_file' => $this->boolean('dpa_on_file'),
        ]);
    }
}
