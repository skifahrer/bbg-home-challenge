<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Electronics', 'Books', 'Clothing', 'Toys', 'Furniture'];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
