<?php

namespace App\Http\Controllers\V2\Admin;

use App\Models\Article;

class ArticleController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return Article::all()->paginate(10);

    }

}
