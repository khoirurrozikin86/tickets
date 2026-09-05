<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:products,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name may not exceed 255 characters.',

            'slug.required' => 'Slug is required.',
            'slug.unique' => 'Slug already exists.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be JPG, JPEG, PNG, or WEBP.',
            'image.max' => 'Image size may not exceed 5 MB.',

            'is_active.required' => 'Status is required.',
            'is_active.boolean' => 'Invalid status.',

            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order cannot be negative.',
        ];
    }

    public function sanitized(): array
    {
        return $this->validated();
    }
}
