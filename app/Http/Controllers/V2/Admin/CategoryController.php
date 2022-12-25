<?php

namespace App\Http\Controllers\V2\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::where('in_menu', true)->get();

    }
    //public routes
    public function getCategories()
    {
        return Category::where('in_menu', true)->get();

    }

}
