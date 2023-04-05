<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use App\Repositories\ArticleRepository;

class HomepageController extends Controller
{

    public function getInitialProps()
    {
//        app()->setLocale(request('locale'));
//        $categories =
//        dump($categories);
        return [
            'categories' => Category::all(),
            'latestPublishedArticles' => Article::getLastPublishedArticles(),
//            'defaultImage' => Image::find(1)->with('thumbnails')
        ];
    }


}
