<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTranslation;

class ArticleController extends Controller {

    public function getAllPublishedArticles()
    {
        return Article::query()->with('category')
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where([
                ['article_translations.status','=','P']
            ])
            ->orderBy('article_translations.published_at', 'DESC')
            ->get();
    }

    public function getArticle($slug)
    {
        $translation = ArticleTranslation::where('slug', $slug)->where('locale', request('locale'))->first();
        $article = Article::find($translation->article_id);
//        dump();
        // increment visit
        visits($article)->increment();

        return $article->load('category','images','images.thumbnails');

    }
}
