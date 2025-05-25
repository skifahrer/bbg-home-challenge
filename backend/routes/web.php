<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/products/{id}', [ProductController::class, 'showProductDetail'])->name('products.detail');
Route::get('/products', [ProductController::class, 'showProductsPage'])->name('products.view');
