<?php

namespace App\Domain\Products\DTOs;

use Illuminate\Http\UploadedFile;

class ProductData
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description,
        public ?string $description_2,
        public mixed $image,
        public bool $is_active,
        public int $sort_order,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'] ?? null,
            description_2: $data['description_2'] ?? null,
            image: $data['image'] ?? null,
            is_active: (bool) ($data['is_active'] ?? false),
            sort_order: (int) ($data['sort_order'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'description_2' => $this->description_2,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];
    }
}
