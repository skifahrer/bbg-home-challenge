<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        foreach (range(1, 15) as $i) {
            Product::create([
                'name' => "Sample Product $i",
                'price' => rand(10, 1000),
                'description' => "This is a sample description for product $i.",
                'category_id' => $categories->random()->id,
                'image_url' => 'https://placehold.co/600x400',
            ]);
        }
    }
}
