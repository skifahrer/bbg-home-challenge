<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocaleSeeder extends Seeder
{
	public function run(): void
	{
		// Remove all entries while respecting foreign key constraints
		DB::table('locales')->delete();

		// Define the locales
		$locales = [
			['locale' => 'en', 'name' => 'English'],
			['locale' => 'sk', 'name' => 'Slovak'],
		];

		// Insert or update
		foreach ($locales as $locale) {
			DB::table('locales')->updateOrInsert(
				['locale' => $locale['locale']],
				['name' => $locale['name']]
			);
		}
	}
}
