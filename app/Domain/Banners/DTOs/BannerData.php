<?php

namespace App\Domain\Banners\DTOs;

class BannerData
{
    public function __construct(
        public ?int $id,
        public ?string $title,
        public ?string $subtitle,
        public string $image,
        public ?string $button_text,
        public ?string $button_url,
        public int $sort_order,
        public bool $is_active,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? null,
            subtitle: $data['subtitle'] ?? null,
            image: $data['image'] ?? '',
            button_text: $data['button_text'] ?? null,
            button_url: $data['button_url'] ?? null,
            sort_order: (int) ($data['sort_order'] ?? 0),
            is_active: (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'image' => $this->image,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    }
}
