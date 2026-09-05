<?php

namespace App\Http\Requests\Admin;

use App\Models\Holiday;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HolidayUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Holiday|null $holiday */
        $holiday = $this->route('holiday');

        return [
            'date' => [
                'required',
                'date',
                Rule::unique('holidays', 'date')
                    ->ignore($holiday?->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
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
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'date.unique' => 'Tanggal tersebut sudah terdaftar.',

            'name.required' => 'Nama hari libur wajib diisi.',
            'name.max' => 'Nama hari libur maksimal 255 karakter.',

            'is_active.required' => 'Status wajib dipilih.',
        ];
    }

    public function sanitized(): array
    {
        return $this->validated();
    }
}
