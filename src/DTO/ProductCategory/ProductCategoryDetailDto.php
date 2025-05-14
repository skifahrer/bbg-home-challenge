<?php

declare(strict_types=1);

namespace App\DTO\ProductCategory;

use App\DTO\Dto;

class ProductCategoryDetailDto implements Dto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        return new self($data['id'], $data['name'], $data['slug']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
