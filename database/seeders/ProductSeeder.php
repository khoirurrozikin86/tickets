<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Reguler',
                'description' => 'Tiket reguler Dusun Semilir.',
                'image' => 'ticket-reguler.jpg',
                'is_active' => true,
                'sort_order' => 1,
                'prices' => [
                    'WEEKDAY' => 40000,
                    'WEEKEND' => 50000,
                    'HOLIDAY' => 50000,
                ],
            ],
            [
                'name' => 'Combo',
                'description' => 'Tiket combo Dusun Semilir.',
                'image' => 'ticket-combo.jpg',
                'is_active' => true,
                'sort_order' => 2,
                'prices' => [
                    'WEEKDAY' => 102000,
                    'WEEKEND' => 127000,
                    'HOLIDAY' => 127000,
                ],
            ],
            [
                'name' => 'Terusan',
                'description' => 'Tiket terusan Dusun Semilir.',
                'image' => 'ticket-terusan.jpg',
                'is_active' => true,
                'sort_order' => 3,
                'prices' => [
                    'WEEKDAY' => 75000,
                    'WEEKEND' => 85000,
                    'HOLIDAY' => 85000,
                ],
            ],
        ];

        foreach ($products as $data) {
            $prices = $data['prices'];

            unset($data['prices']);

            $product = Product::updateOrCreate(
                [
                    'slug' => Str::slug($data['name']),
                ],
                $data
            );

            foreach ($prices as $dayType => $price) {
                ProductPrice::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'day_type' => $dayType,
                    ],
                    [
                        'price' => $price,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
