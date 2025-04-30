<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a paginated list of products.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        return response()->json(
            $query->with('category')->paginate(10)
        );
    }

    /**
     * Display the specified product by its ID.
     *
     * @param int $id The ID of the product to retrieve.
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the product is not found.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json($product);
    }
}
