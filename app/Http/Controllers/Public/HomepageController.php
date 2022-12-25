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

        return [
            'latestPublishedArticles' => Article::getLastPublishedArticles(),
            'categories' => Category::where('in_menu', true)->get(),
            'defaultImage' => Image::find(1)->load('thumbnails')
        ];
    }


}
