<?php

namespace App\Http\Requests\Admin\AuditLogs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAuditLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => [
                'required',
                'string',
                'max:50',
            ],

            'module' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'old_values' => [
                'nullable',
                'array',
            ],

            'new_values' => [
                'nullable',
                'array',
            ],
        ];
    }

    public function sanitized(): array
    {
        return $this->validated();
    }
}
