<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
	public function run()
	{
		DB::table('products')->delete();

		// Fetch locale IDs
		$locales = DB::table('locales')->pluck('id', 'locale')->toArray();

		if (!isset($locales['en'], $locales['sk'])) {
			throw new \Exception('Required locales not found in the database.');
		}

		$categoryIds = range(1, 5); // Define category ID range

		for ($i = 1; $i <= 15; $i++) {
			// Choose a random category_id from 1 to 5
			$randomCategoryId = $categoryIds[array_rand($categoryIds)];

			// Create English products
			Product::create([
				'product_id' => $i,
				'name' => 'Product ' . $i,
				'price' => rand(10, 100),
				'description' => 'Description for product ' . $i,
				'category_id' => $randomCategoryId,
				'image_url' => 'https://res.cloudinary.com/chal-tec/image/upload/w_300,q_auto,f_auto,dpr_1.0/bbg/10033801/Gallery/10033801_uk_0001_main___.jpg',//750
				'locale_id' => $locales['en'],
			]);

			// Create Slovak products
			Product::create([
				'product_id' => $i,
				'name' => 'Produkt ' . $i,
				'price' => rand(10, 100),
				'description' => 'Popis pre produkt ' . $i,
				'category_id' => $randomCategoryId,
				'image_url' => 'https://res.cloudinary.com/chal-tec/image/upload/w_300,q_auto,f_auto,dpr_1.0/bbg/10005315/Gallery/10005315_yy_0001_titel___.jpg',//750
				'locale_id' => $locales['sk'],
			]);
		}
	}
}
