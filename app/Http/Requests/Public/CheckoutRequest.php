<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product' => [
                'required',
                'string',
                'exists:products,slug',
            ],

            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'voucher' => [
                'nullable',
                'string',
                'max:50',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product.required' => 'Produk tiket wajib dipilih.',
            'product.exists' => 'Produk tiket tidak ditemukan.',

            'date.required' => 'Tanggal kunjungan wajib dipilih.',
            'date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.',

            'quantity.required' => 'Jumlah tiket wajib diisi.',
            'quantity.min' => 'Minimal pembelian 1 tiket.',
            'quantity.max' => 'Maksimal pembelian 20 tiket.',

            'name.required' => 'Nama pemesan wajib diisi.',
            'email.required' => 'Email pemesan wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
        ];
    }
}