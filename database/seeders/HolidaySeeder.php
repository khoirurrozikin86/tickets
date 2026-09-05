<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            [
                'date' => '2026-01-01',
                'name' => 'Tahun Baru Masehi',
            ],
            [
                'date' => '2026-03-19',
                'name' => 'Nyepi',
            ],
            [
                'date' => '2026-04-03',
                'name' => 'Wafat Yesus Kristus',
            ],
            [
                'date' => '2026-04-05',
                'name' => 'Paskah',
            ],
            [
                'date' => '2026-05-01',
                'name' => 'Hari Buruh Internasional',
            ],
            [
                'date' => '2026-05-14',
                'name' => 'Kenaikan Yesus Kristus',
            ],
            [
                'date' => '2026-05-27',
                'name' => 'Idul Adha',
            ],
            [
                'date' => '2026-06-01',
                'name' => 'Hari Lahir Pancasila',
            ],
            [
                'date' => '2026-06-17',
                'name' => '1 Muharam / Tahun Baru Islam',
            ],
            [
                'date' => '2026-08-17',
                'name' => 'Hari Kemerdekaan Republik Indonesia',
            ],
            [
                'date' => '2026-12-25',
                'name' => 'Hari Raya Natal',
            ],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                [
                    'date' => $holiday['date'],
                ],
                [
                    'name' => $holiday['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
