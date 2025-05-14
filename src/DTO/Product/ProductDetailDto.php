<?php

declare(strict_types=1);

namespace App\DTO\Product;

use App\DTO\Dto;
use App\DTO\ProductCategory\ProductCategoryDetailDto;

class ProductDetailDto implements Dto
{
    public function __construct(
        public int $id,
        public ProductCategoryDetailDto $category,
        public int $price,
        public string $name,
        public string $slug,
        public string $imageUrl,
        public string $description,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        $category = new ProductCategoryDetailDto(
            (int) $data['id_category'],
            (string)$data['category_name'],
            (string)$data['category_slug'],
        );

        return new self(
            (int) $data['id'],
            $category,
            (int) $data['price'],
            (string) $data['name'],
            (string) $data['slug'],
            (string) $data['image_url'],
            (string) $data['description']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category->toArray(),
            'price' => $this->price,
            'name' => $this->name,
            'slug' => $this->slug,
            'image_url' => $this->imageUrl,
            'description' => $this->description,
        ];
    }
}
