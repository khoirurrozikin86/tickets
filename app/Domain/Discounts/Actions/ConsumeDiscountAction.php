<?php

namespace App\Domain\Discounts\Actions;

use App\Models\Discount;
use App\Models\DiscountUsage;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ConsumeDiscountAction
{
    public function execute(Order $order): ?DiscountUsage
    {
        if (
            ! $order->discount_id ||
            (float) $order->discount_amount <= 0
        ) {
            return null;
        }

        return DB::transaction(function () use ($order) {

            /*
             * Lock discount untuk mencegah race condition
             * ketika beberapa order menggunakan voucher
             * secara bersamaan.
             */
            $discount = Discount::query()
                ->whereKey($order->discount_id)
                ->lockForUpdate()
                ->first();

            if (! $discount) {
                throw new RuntimeException(
                    "Discount ID {$order->discount_id} tidak ditemukan."
                );
            }

            /*
             * Idempotency.
             *
             * Satu order hanya boleh dihitung satu kali.
             */
            $existingUsage = DiscountUsage::query()
                ->where('discount_id', $discount->id)
                ->where('order_id', $order->id)
                ->first();

            if ($existingUsage) {
                return $existingUsage;
            }

            /*
             * Cek usage limit.
             */
            if (
                $discount->usage_limit !== null &&
                $discount->usage_count >= $discount->usage_limit
            ) {
                throw new RuntimeException(
                    "Discount {$discount->code} sudah mencapai batas penggunaan."
                );
            }

            /*
             * Tambah usage count.
             */
            $discount->increment('usage_count');

            /*
             * Catat penggunaan discount.
             */
            return DiscountUsage::create([
                'discount_id' => $discount->id,
                'order_id' => $order->id,
                'discount_amount' => $order->discount_amount,
                'used_at' => now(),
            ]);
        });
    }
}
