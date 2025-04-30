<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->productName ?? $this->faker->words(2, true),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'description' => $this->faker->sentence,
            'category_id' => Category::factory(),
            'image_url' => 'https://via.placeholder.com/150',
        ];
    }
}
