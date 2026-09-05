<?php

namespace App\Domain\SiteSettings\DTOs;

class SiteSettingData
{
    public function __construct(
        public string $key,
        public string $label,
        public ?string $value = null,
        public string $type = 'text',
        public string $group = 'GENERAL',
        public ?string $description = null,
        public bool $is_active = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            key: $data['key'],
            label: $data['label'],
            value: $data['value'] ?? null,
            type: $data['type'] ?? 'text',
            group: $data['group'] ?? 'GENERAL',
            description: $data['description'] ?? null,
            is_active: (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'value' => $this->value,
            'type' => $this->type,
            'group' => $this->group,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
    }
}
