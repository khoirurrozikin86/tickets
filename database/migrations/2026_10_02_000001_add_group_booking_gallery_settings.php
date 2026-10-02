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
                'key' => 'group_booking_whatsapp',
                'label' => 'WhatsApp Sales Rombongan',
                'value' => '08112747724',
                'type' => 'phone',
                'group' => 'OSIL',
                'description' => 'Nomor WhatsApp sales untuk pemesanan tiket rombongan.',
            ],
        ];

        foreach (range(1, 6) as $slot) {
            $settings[] = [
                'key' => 'group_gallery_' . $slot,
                'label' => 'Foto Rombongan ' . $slot,
                'value' => null,
                'type' => 'image',
                'group' => 'OSIL',
                'description' => 'Foto galeri pemesanan rombongan.',
            ];
        }

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
            ->whereIn('key', [
                'group_booking_whatsapp',
                'group_gallery_1',
                'group_gallery_2',
                'group_gallery_3',
                'group_gallery_4',
                'group_gallery_5',
                'group_gallery_6',
            ])
            ->delete();
    }
};