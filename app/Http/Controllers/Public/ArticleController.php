<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
class ArticleController extends Controller {

    public function getAllPublishedArticles(Type $var = null)
    {
        return Article::query()
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where([
                ['article_translations.status','=','P']
            ])
            ->orderBy('article_translations.published_at', 'DESC')
            ->get();
    }
}
