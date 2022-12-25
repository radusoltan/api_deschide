<?php

namespace App\Http\Controllers\Public;

use App\Models\Article;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function getAllPublishedCategories()
    {
        $locale = request('locale');

        // app()->setLocale($locale);

        return Category::where('in_menu', true)->get();

    }

    public function getCategory($slug)
    {
        $translation = CategoryTranslation::where('slug', $slug)->where('locale', request('locale'))->first();
        return Category::find($translation->category_id);


    }

    public function getCategoryPublishedArticles(Category $category)
    {

        return $category->getPublishedArticles(); //Article::findMany($ids);


    }



}
