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

            'settings.footer_tagline' => ['nullable', 'string', 'max:255'],
            'settings.footer_description' => ['nullable', 'string', 'max:1000'],
            'settings.footer_promo' => ['nullable', 'string', 'max:255'],
            'settings.event_enabled' => ['required', 'boolean'],
            'settings.event_title' => ['nullable', 'string', 'max:150'],
            'settings.event_description' => ['nullable', 'string', 'max:10000'],

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

            'settings.group_booking_whatsapp' => [
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

            'files.group_gallery_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=800,min_height=600'],
            'files.group_gallery_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=800,min_height=600'],
            'files.group_gallery_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=800,min_height=600'],
            'files.group_gallery_4' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=800,min_height=600'],
            'files.group_gallery_5' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=800,min_height=600'],
            'files.group_gallery_6' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=800,min_height=600'],
            'files.event_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'dimensions:min_width=800,min_height=600'],
        ];
    }
}
