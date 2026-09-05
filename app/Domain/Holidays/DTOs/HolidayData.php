<?php

namespace App\Domain\Holidays\DTOs;

class HolidayData
{
    public function __construct(
        public string $date,
        public string $name,
        public bool $is_active = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'],
            name: $data['name'],
            is_active: (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'name' => $this->name,
            'is_active' => $this->is_active,
        ];
    }
}
