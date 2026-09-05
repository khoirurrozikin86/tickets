<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\Product;
use Carbon\Carbon;
use RuntimeException;

class PriceResolver
{
    public function getDayType(string $date): string
    {
        $date = Carbon::parse($date);

        // 1. Holiday memiliki prioritas paling tinggi
        $isHoliday = Holiday::whereDate('date', $date)
            ->where('is_active', true)
            ->exists();

        if ($isHoliday) {
            return 'HOLIDAY';
        }

        // 2. Sabtu / Minggu
        if ($date->isWeekend()) {
            return 'WEEKEND';
        }

        // 3. Senin - Jumat
        return 'WEEKDAY';
    }

    public function getPrice(Product $product, string $date): float
    {
        $dayType = $this->getDayType($date);

        $price = $product->prices()
            ->where('day_type', $dayType)
            ->where('is_active', true)
            ->value('price');

        if ($price === null) {
            throw new RuntimeException(
                "Harga {$dayType} untuk produk {$product->name} belum tersedia."
            );
        }

        return (float) $price;
    }

    public function resolve(Product $product, string $date): array
    {
        $dayType = $this->getDayType($date);

        $price = $product->prices()
            ->where('day_type', $dayType)
            ->where('is_active', true)
            ->value('price');

        if ($price === null) {
            throw new RuntimeException(
                "Harga {$dayType} untuk produk {$product->name} belum tersedia."
            );
        }

        return [
            'date' => Carbon::parse($date)->toDateString(),
            'day_type' => $dayType,
            'price' => (float) $price,
        ];
    }
}
