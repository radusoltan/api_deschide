<?php

namespace App\Http\Controllers\Public;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function getAllPublishedCategories()
    {
        $locale = request('locale');

        // app()->setLocale($locale);

        $categories = Category::all();

        return $categories;

    }



}
