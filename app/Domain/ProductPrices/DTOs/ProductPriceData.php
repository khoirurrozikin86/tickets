<?php

namespace App\Domain\ProductPrices\DTOs;

class ProductPriceData
{
    public function __construct(
        public int $product_id,
        public string $day_type,
        public float $price,
        public bool $is_active = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_id: (int) $data['product_id'],
            day_type: strtoupper($data['day_type']),
            price: (float) $data['price'],
            is_active: (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'day_type' => $this->day_type,
            'price' => $this->price,
            'is_active' => $this->is_active,
        ];
    }
}
