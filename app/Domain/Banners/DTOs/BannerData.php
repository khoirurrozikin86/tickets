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
        public ?string $pdf_position,
        public ?string $tracking_source,
        public ?string $tracking_medium,
        public ?string $tracking_campaign,
        public ?string $tracking_content,
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
            pdf_position: $data['pdf_position'] ?? null,
            tracking_source: $data['tracking_source'] ?? null,
            tracking_medium: $data['tracking_medium'] ?? null,
            tracking_campaign: $data['tracking_campaign'] ?? null,
            tracking_content: $data['tracking_content'] ?? null,
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
            'pdf_position' => $this->pdf_position,
            'tracking_source' => $this->tracking_source,
            'tracking_medium' => $this->tracking_medium,
            'tracking_campaign' => $this->tracking_campaign,
            'tracking_content' => $this->tracking_content,
        ];
    }
}
