<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectAccessRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Real authorization happens via the SubjectAccessRequestPolicy,
        // called explicitly in the controller. This just gates the request shape.
        return true;
    }

    public function rules(): array
    {
        return [
            'requester_name' => ['required', 'string', 'max:255'],
            'requester_type' => ['required', 'in:customer,employee,applicant,other'],
            'received_at' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
