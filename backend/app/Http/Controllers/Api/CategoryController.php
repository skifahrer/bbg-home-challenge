<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
	private function resolveLocaleId(string $localeCode): ?int
	{
		$localeId = DB::table('locales')->where('locale', $localeCode)->value('id');
		return $localeId ?: null;
	}

	/**
	 * Display a list of categories for a specified locale.
	 *
	 * This method retrieves categories that match the provided locale, returning
	 * them as a JSON response. If the locale is not found, it returns a JSON error
	 * message with a 404 status code.
	 *
	 * @param Request $request The incoming request containing query parameters.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing the category list for the specified locale or an error message.
	 */

	public function index(Request $request)
	{
		$localeId = $this->resolveLocaleId($request->query('locale', config('app.locale')));

		// Check if the locale was not found
		if (is_null($localeId)) {
			// Return a JSON error response if locale not found
			return response()->json(['error' => __('texts.locale_not_found')], 404);
		}

		// Get categories with the specified locale ID
		$categories = Category::where('locale_id', $localeId)
			->select('category_id', 'name')
			->get();

		// Return the categories as a JSON response
		return response()->json($categories);
	}
}
