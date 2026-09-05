<?php

namespace App\Domain\Discounts\DTOs;

class DiscountData
{
    public function __construct(
        public string $code,
        public string $name,
        public string $type,
        public float $value,
        public ?float $max_discount = null,
        public float $min_purchase = 0,
        public ?string $start_at = null,
        public ?string $end_at = null,
        public ?int $usage_limit = null,
        public int $usage_count = 0,
        public bool $is_active = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: strtoupper(trim($data['code'])),
            name: $data['name'],
            type: strtoupper($data['type']),
            value: (float) $data['value'],
            max_discount: isset($data['max_discount']) && $data['max_discount'] !== ''
                ? (float) $data['max_discount']
                : null,
            min_purchase: (float) ($data['min_purchase'] ?? 0),
            start_at: $data['start_at'] ?? null,
            end_at: $data['end_at'] ?? null,
            usage_limit: isset($data['usage_limit']) && $data['usage_limit'] !== ''
                ? (int) $data['usage_limit']
                : null,
            usage_count: (int) ($data['usage_count'] ?? 0),
            is_active: (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->value,
            'max_discount' => $this->max_discount,
            'min_purchase' => $this->min_purchase,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'usage_limit' => $this->usage_limit,
            'usage_count' => $this->usage_count,
            'is_active' => $this->is_active,
        ];
    }
}
