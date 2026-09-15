<?php

namespace App\Domain\Checkout\Services;

use App\Models\Discount;
use App\Models\Product;
use App\Services\PriceResolver;
use Carbon\Carbon;

class CheckoutPricingService
{
    public function __construct(
        private readonly PriceResolver $priceResolver,
    ) {}

    public function calculate(
        Product $product,
        string $date,
        int $quantity,
        ?string $voucher = null,
    ): array {
        $resolved = $this->priceResolver->resolve(
            $product,
            $date
        );

        $unitPrice = (float) $resolved['price'];

        $subtotal = $unitPrice * $quantity;

        $discount = null;
        $discountAmount = 0;

        if ($voucher) {
            $discount = Discount::query()
                ->where(
                    'code',
                    strtoupper(trim($voucher))
                )
                ->where('is_active', true)
                ->first();

            if ($discount) {
                $this->validateDiscount(
                    $discount,
                    $subtotal
                );

                $discountAmount = $this->calculateDiscount(
                    $discount,
                    $subtotal
                );
            }
        }

        $total = max(
            0,
            $subtotal - $discountAmount
        );

        return [
            'date' => $resolved['date'],

            'day_type' => $resolved['day_type'],

            'unit_price' => $unitPrice,

            'quantity' => $quantity,

            'subtotal' => $subtotal,

            'discount' => $discount,

            'discount_amount' => $discountAmount,

            'total' => $total,
        ];
    }

    private function validateDiscount(
        Discount $discount,
        float $subtotal
    ): void {
        $now = now();

        if (
            $discount->start_at &&
            $now->lt($discount->start_at)
        ) {
            throw new \DomainException(
                'Voucher belum dapat digunakan.'
            );
        }

        if (
            $discount->end_at &&
            $now->gt($discount->end_at)
        ) {
            throw new \DomainException(
                'Voucher sudah berakhir.'
            );
        }

        if (
            $discount->usage_limit !== null &&
            $discount->usage_count >= $discount->usage_limit
        ) {
            throw new \DomainException(
                'Voucher sudah mencapai batas penggunaan.'
            );
        }

        if (
            $subtotal <
            (float) $discount->min_purchase
        ) {
            throw new \DomainException(
                'Minimal pembelian untuk voucher ini adalah Rp ' .
                number_format(
                    (float) $discount->min_purchase,
                    0,
                    ',',
                    '.'
                ) .
                '.'
            );
        }
    }

    private function calculateDiscount(
        Discount $discount,
        float $subtotal
    ): float {
        if ($discount->type === 'PERCENTAGE') {
            $discountAmount =
                $subtotal *
                ((float) $discount->value / 100);

            if ($discount->max_discount !== null) {
                $discountAmount = min(
                    $discountAmount,
                    (float) $discount->max_discount
                );
            }
        } else {
            $discountAmount =
                (float) $discount->value;
        }

        return min(
            $discountAmount,
            $subtotal
        );
    }
}