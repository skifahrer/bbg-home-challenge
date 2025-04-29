<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);    // GET /api/products
    Route::get('{id}', [ProductController::class, 'show']);   // GET /api/products/{id}
});

Route::get('categories', [CategoryController::class, 'index']); // GET /api/categories
