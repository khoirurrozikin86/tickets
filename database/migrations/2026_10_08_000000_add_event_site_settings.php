<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('site_settings')->insertOrIgnore([
            [
                'key' => 'event_enabled',
                'label' => 'Tampilkan Section Event',
                'value' => '0',
                'type' => 'toggle',
                'group' => 'EVENT',
                'description' => 'Aktifkan atau sembunyikan section event di halaman utama.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'event_title',
                'label' => 'Judul Event',
                'value' => 'Event & Aktivitas',
                'type' => 'text',
                'group' => 'EVENT',
                'description' => 'Judul yang tampil di atas section event.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'event_description',
                'label' => 'Deskripsi Event',
                'value' => 'Nikmati berbagai event dan aktivitas seru bersama keluarga di Dusun Semilir.',
                'type' => 'textarea',
                'group' => 'EVENT',
                'description' => 'Isi deskripsi yang tampil di samping gambar. Baris baru akan dipertahankan.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'event_image',
                'label' => 'Gambar Event',
                'value' => null,
                'type' => 'image',
                'group' => 'EVENT',
                'description' => 'JPG, PNG, atau WEBP. Minimal 800 × 600 px; disarankan 1200 × 900 px; maksimal 5 MB.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->whereIn('key', [
                'event_enabled',
                'event_title',
                'event_description',
                'event_image',
            ])
            ->delete();
    }
};