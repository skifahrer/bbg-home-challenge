<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     *
     * This method retrieves all records from the Category model
     * and returns them as a collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Category[]
     */
    public function index()
    {
        return Category::all();
    }
}
