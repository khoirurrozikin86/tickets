<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'label' => 'Nama Website',
                'value' => 'Dusun Semilir',
                'type' => 'text',
                'group' => 'GENERAL',
                'description' => 'Nama website.',
                'is_active' => true,
            ],

            [
                'key' => 'site_tagline',
                'label' => 'Tagline',
                'value' => 'Liburan Lebih Seru Bersama OSIL!',
                'type' => 'text',
                'group' => 'GENERAL',
                'description' => 'Tagline website.',
                'is_active' => true,
            ],

            [
                'key' => 'logo',
                'label' => 'Logo',
                'value' => 'images/semilir_logo.png',
                'type' => 'image',
                'group' => 'BRAND',
                'description' => 'Logo utama website.',
                'is_active' => true,
            ],

            [
                'key' => 'favicon',
                'label' => 'Favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'BRAND',
                'description' => 'Favicon website.',
                'is_active' => true,
            ],

            [
                'key' => 'email',
                'label' => 'Email',
                'value' => 'info@dusunsemilir.com',
                'type' => 'email',
                'group' => 'CONTACT',
                'description' => 'Email resmi website.',
                'is_active' => true,
            ],

            [
                'key' => 'phone',
                'label' => 'Telepon',
                'value' => '08112747724',
                'type' => 'phone',
                'group' => 'CONTACT',
                'description' => 'Nomor telepon.',
                'is_active' => true,
            ],

            [
                'key' => 'whatsapp',
                'label' => 'WhatsApp',
                'value' => '08112747724',
                'type' => 'phone',
                'group' => 'CONTACT',
                'description' => 'Nomor WhatsApp.',
                'is_active' => true,
            ],

            [
                'key' => 'address',
                'label' => 'Alamat',
                'value' => 'Jl. Soekarno - Hatta No.49, Ngemble, Bawen, Ngemplak, Kabupaten Semarang, Jawa Tengah',
                'type' => 'textarea',
                'group' => 'CONTACT',
                'description' => 'Alamat lokasi.',
                'is_active' => true,
            ],

            [
                'key' => 'instagram',
                'label' => 'Instagram',
                'value' => 'https://www.instagram.com/dusunsemilir',
                'type' => 'url',
                'group' => 'SOCIAL',
                'description' => 'URL Instagram.',
                'is_active' => true,
            ],

            [
                'key' => 'facebook',
                'label' => 'Facebook',
                'value' => 'https://www.facebook.com/dusunsemilir',
                'type' => 'url',
                'group' => 'SOCIAL',
                'description' => 'URL Facebook.',
                'is_active' => true,
            ],

            [
                'key' => 'tiktok',
                'label' => 'TikTok',
                'value' => 'https://www.tiktok.com/@dusunsemilir',
                'type' => 'url',
                'group' => 'SOCIAL',
                'description' => 'URL TikTok.',
                'is_active' => true,
            ],

            [
                'key' => 'youtube',
                'label' => 'YouTube',
                'value' => 'https://www.youtube.com/@dusunsemilir',
                'type' => 'url',
                'group' => 'SOCIAL',
                'description' => 'URL YouTube.',
                'is_active' => true,
            ],

            [
                'key' => 'copyright',
                'label' => 'Copyright',
                'value' => '© 2026 Dusun Semilir. All rights reserved.',
                'type' => 'text',
                'group' => 'FOOTER',
                'description' => 'Copyright footer.',
                'is_active' => true,
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                [
                    'key' => $setting['key'],
                ],
                $setting
            );
        }

        $this->command?->info(
            'Site settings berhasil di-seed: ' . count($settings) . ' data.'
        );
    }
}
