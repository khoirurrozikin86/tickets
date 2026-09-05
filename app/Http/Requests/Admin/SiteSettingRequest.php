<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $settingId = $this->route('siteSetting')?->id;

        return [
            'key' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('site_settings', 'key')
                    ->ignore($settingId),
            ],

            'label' => [
                'required',
                'string',
                'max:150',
            ],

            'value' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                Rule::in([
                    'text',
                    'textarea',
                    'image',
                    'url',
                    'email',
                    'phone',
                ]),
            ],

            'group' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
