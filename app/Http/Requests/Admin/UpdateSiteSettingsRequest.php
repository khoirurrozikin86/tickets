<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings' => [
                'required',
                'array',
            ],

            'settings.site_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'settings.site_tagline' => [
                'nullable',
                'string',
                'max:255',
            ],

            'settings.email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'settings.phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'settings.whatsapp' => [
                'nullable',
                'string',
                'max:30',
            ],

            'settings.address' => [
                'nullable',
                'string',
            ],

            'settings.instagram' => [
                'nullable',
                'url',
                'max:255',
            ],

            'settings.facebook' => [
                'nullable',
                'url',
                'max:255',
            ],

            'settings.tiktok' => [
                'nullable',
                'url',
                'max:255',
            ],

            'settings.youtube' => [
                'nullable',
                'url',
                'max:255',
            ],

            'settings.copyright' => [
                'nullable',
                'string',
                'max:255',
            ],

            'files' => [
                'nullable',
                'array',
            ],

            'files.*' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,svg',
                'max:5120',
            ],
        ];
    }
}
