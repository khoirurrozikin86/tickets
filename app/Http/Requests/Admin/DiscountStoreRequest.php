<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiscountStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                'unique:discounts,code',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                Rule::in([
                    'PERCENTAGE',
                    'FIXED',
                ]),
            ],

            'value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'max_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'min_purchase' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'start_at' => [
                'nullable',
                'date',
            ],

            'end_at' => [
                'nullable',
                'date',
                'after_or_equal:start_at',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            if (
                $this->type === 'PERCENTAGE' &&
                (float) $this->value > 100
            ) {
                $validator->errors()->add(
                    'value',
                    'Persentase diskon tidak boleh lebih dari 100%.'
                );
            }
        });
    }

    public function sanitized(): array
    {
        return $this->validated();
    }
}
