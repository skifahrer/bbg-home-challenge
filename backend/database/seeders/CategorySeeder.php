<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
	public function run()
	{

		DB::table('categories')->delete();

		// Fetch existing locales
		$locales = DB::table('locales')->pluck('id', 'locale')->toArray();

		// Ensure 'en' and 'sk' locales are available
		if (!isset($locales['en']) || !isset($locales['sk'])) {
			throw new \Exception('Required locales not found in the database.');
		}

		// Categories defined by locale with shared category_id
		$categories = [
			1 => ['en' => 'Heaters', 'sk' => 'Ohrievače'],
			2 => ['en' => 'Coolers', 'sk' => 'Chladiče'],
			3 => ['en' => 'BBQ Grills', 'sk' => 'Grily'],
			4 => ['en' => 'Garden', 'sk' => 'Záhrada'],
			5 => ['en' => 'Audio & Hi-fi', 'sk' => 'Audio & Hi-fi'],
		];

		foreach ($categories as $categoryId => $names) {
			foreach ($locales as $locale => $localeId) {
				Category::updateOrCreate(
					['category_id' => $categoryId, 'locale_id' => $localeId],
					['name' => $names[$locale], 'locale_id' => $localeId, 'category_id' => $categoryId]
				);
			}
		}
	}
}
