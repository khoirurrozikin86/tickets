<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $settings = [
            [
                'key' => 'footer_tagline',
                'label' => 'Footer Tagline',
                'value' => 'Wisata Keluarga, Cerita Tak Terlupa',
                'type' => 'text',
                'group' => 'FOOTER',
                'description' => 'Tagline pada bagian brand footer.',
            ],
            [
                'key' => 'footer_description',
                'label' => 'Footer Description',
                'value' => 'Nikmati pengalaman wisata yang seru, nyaman, dan menyenangkan bersama keluarga.',
                'type' => 'textarea',
                'group' => 'FOOTER',
                'description' => 'Deskripsi singkat pada footer.',
            ],
            [
                'key' => 'footer_promo',
                'label' => 'Footer Promo Text',
                'value' => 'Liburan lebih seru bersama Dusun Semilir',
                'type' => 'text',
                'group' => 'FOOTER',
                'description' => 'Kalimat promo pada footer.',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $setting['key']],
                [...$setting, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->whereIn('key', ['footer_tagline', 'footer_description', 'footer_promo'])
            ->delete();
    }
};
