<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'code' => 'DUSEM10',
                'name' => 'Promo Dusun Semilir 10%',
                'type' => 'PERCENTAGE',
                'value' => 10,
                'max_discount' => 50000,
                'min_purchase' => 100000,
                'start_at' => '2026-09-01 00:00:00',
                'end_at' => '2026-12-31 23:59:59',
                'usage_limit' => 1000,
                'usage_count' => 0,
                'is_active' => true,
            ],

            [
                'code' => 'DUSEM50K',
                'name' => 'Promo Dusun Semilir 50K',
                'type' => 'FIXED',
                'value' => 50000,
                'max_discount' => null,
                'min_purchase' => 300000,
                'start_at' => '2026-09-01 00:00:00',
                'end_at' => '2026-12-31 23:59:59',
                'usage_limit' => 500,
                'usage_count' => 0,
                'is_active' => true,
            ],

            [
                'code' => 'WEEKEND20',
                'name' => 'Promo Weekend 20%',
                'type' => 'PERCENTAGE',
                'value' => 20,
                'max_discount' => 100000,
                'min_purchase' => 200000,
                'start_at' => '2026-09-01 00:00:00',
                'end_at' => '2026-12-31 23:59:59',
                'usage_limit' => 300,
                'usage_count' => 0,
                'is_active' => true,
            ],

            [
                'code' => 'WELCOME25K',
                'name' => 'Welcome Discount 25K',
                'type' => 'FIXED',
                'value' => 25000,
                'max_discount' => null,
                'min_purchase' => 150000,
                'start_at' => '2026-09-01 00:00:00',
                'end_at' => '2026-12-31 23:59:59',
                'usage_limit' => 1000,
                'usage_count' => 0,
                'is_active' => true,
            ],

            [
                'code' => 'TEST10',
                'name' => 'Test Discount 10%',
                'type' => 'PERCENTAGE',
                'value' => 10,
                'max_discount' => 25000,
                'min_purchase' => 0,
                'start_at' => null,
                'end_at' => null,
                'usage_limit' => null,
                'usage_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($discounts as $discount) {
            Discount::updateOrCreate(
                [
                    'code' => $discount['code'],
                ],
                $discount
            );
        }
    }
}
