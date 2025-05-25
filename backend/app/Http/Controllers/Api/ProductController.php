<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{

	// Resolves the ID for a given locale code, returns null if not found
	private function resolveLocaleId(string $localeCode): ?int
	{
		$localeId = DB::table('locales')->where('locale', $localeCode)->value('id');
		return $localeId ?: null;  // Return null if no ID is found
	}

	// Retrieves a list of all available locale codes from the database
	private function getAvailableLocales(): array
	{
		return DB::table('locales')->pluck('locale')->toArray();
	}

	/**
	 * Display a listing of the products based on specified filters.
	 *
	 * This method handles the retrieval of a paginated list of products.
	 * It processes query parameters for locale, pagination, category,
	 * and search terms to filter and format the product data returned
	 * in a JSON response.
	 *
	 * @param Request $request The incoming request containing query parameters.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing the filtered products and pagination metadata.
	 */

	public function index(Request $request)
	{
		// Get available locales for user selection
		$localeId = $this->resolveLocaleId($request->query('locale', config('app.locale')));

		// Handle missing locale
		if (is_null($localeId)) {
			return response()->json(['error' => __('texts.locale_not_found')], 404);
		}

		// Get per-page count, capped at 10
		$perPage = min((int) $request->query('per_page', 10), 10);

		// Build initial query for products with resolved locale
		$query = Product::where('locale_id', $localeId);

		// Filter by category if provided
		if ($categoryId = $request->query('category_id')) {
			$query->where('category_id', $categoryId);
		}

		// Apply search filter if a term is provided
		if ($search = $request->query('search')) {
			$query->where('name', 'like', '%' . $search . '%');
		}

		// Paginate results based on the per-page count
		$products = $query->paginate($perPage);

		// Return the paginated results as a JSON response
		return response()->json([
			'data' => $products->items(),
			'meta' => [
				'current_page' => $products->currentPage(),
				'total_pages' => $products->lastPage(),
				'total_products' => $products->total(),
				'per_page' => $products->perPage(),
			],
		]);
	}

	/**
	 * Display the specified product details.
	 *
	 * This method retrieves and returns the details of a specific product
	 * based on its product ID. If the product is not found, it returns a 404 HTTP status code.
	 *
	 * @param int $id The ID of the product to retrieve.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing the product details or an error message.
	 */
	
	public function show($id)
	{
		// Look up the product by its ID
		$product = Product::where('product_id', $id)->first();

		// Check if product was not found
		if (!$product) {
			// Return a JSON response with an error message and 404 status
			return response()->json(['error' => __('texts.product_not_found')], 404);
		}

		// Return the product details as a JSON response
		return response()->json($product);
	}

	// Method to show products list
	public function showProductsPage(Request $request)
	{
		// Get available locales for user selection
		$locales = $this->getAvailableLocales();
		$localeId = $this->resolveLocaleId($request->query('locale', config('app.locale')));

		// Default to "All Categories"
		$categoryName = __('texts.all_categories');
		$title = $categoryName;

		// Check for category ID in query
		if ($categoryId = $request->query('category_id')) {
			// Find category by ID and locale
			$category = Category::where('category_id', $categoryId)
				->where('locale_id', $localeId)
				->first();

			// Update name and title if found
			if ($category) {
				$categoryName = $category->name;
				$title = $categoryName;
			}
		}

		// Update title for search term
		if ($searchTerm = $request->query('search')) {
			$title = __('texts.search_for') . ' "' . $searchTerm . '" ' . __('texts.search_in') . ' ' . $categoryName;
		}

		// Render the products view
		return view('products', compact('locales', 'categoryName', 'title'));
	}

	// Method to show product detail
	public function showProductDetail($id, Request $request)
	{
		// Get available locales for user selection
		$locales = $this->getAvailableLocales();
		$localeId = $this->resolveLocaleId($request->query('locale', config('app.locale')));

		if (is_null($localeId)) {
			// If locale not found, prepare error message and fallback data
			$error = __('texts.locale_not_found');
			$product = [];

			// Pass error and data to the view
			return view('product-detail', compact('product', 'locales', 'error'));
		}

		// Retrieve the product using the product_id and locale_id columns
		$product = Product::where('product_id', $id)
			->where('locale_id', $localeId)
			->firstOrFail();

		if (is_null($product)) {
			$error = __('texts.product_not_found');
			$product = [];

			// Pass error and data to the view
			return view('product-detail', compact('product', 'locales', 'error'));
		}
		// Pass the product and locales to the view
		return view('product-detail', compact('product', 'locales'));
	}

	public function changeLanguage($locale)
	{
		Session::put('locale', $locale);
		return redirect()->back();
	}
}
