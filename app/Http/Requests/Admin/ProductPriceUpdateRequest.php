<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductPriceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productPrice = $this->route('productPrice');

        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'day_type' => [
                'required',
                Rule::in([
                    'WEEKDAY',
                    'WEEKEND',
                    'HOLIDAY',
                ]),
                Rule::unique('product_prices', 'day_type')
                    ->where('product_id', $this->product_id)
                    ->ignore($productPrice?->id),
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product wajib dipilih.',
            'product_id.exists' => 'Product tidak ditemukan.',

            'day_type.required' => 'Tipe hari wajib dipilih.',
            'day_type.in' => 'Tipe hari tidak valid.',
            'day_type.unique' => 'Harga untuk tipe hari tersebut sudah ada.',

            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',

            'is_active.required' => 'Status wajib dipilih.',
            'is_active.boolean' => 'Status tidak valid.',
        ];
    }

    public function sanitized(): array
    {
        return $this->validated();
    }
}
