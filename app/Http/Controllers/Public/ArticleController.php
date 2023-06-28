<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleList;
use App\Models\ArticleTranslation;
use App\Models\Category;

class ArticleController extends Controller {

    public function getAllPublishedArticles()
    {

        return Article::query()->with('category','images', 'images.thumbnails')
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where([
                ['article_translations.status','=','P'],
                ['article_translations.locale','=', request('locale')]
            ])
            ->orderBy('article_translations.published_at', 'DESC')
            ->paginate();
    }

    public function getArticle($slug)
    {
        $translation = ArticleTranslation::where('slug', $slug)->where('locale', request('locale'))->first();
        $article = Article::find($translation->article_id);
//        dump();
        // increment visit
        visits($article)->increment();

        return $article->load('category','images','images.thumbnails', 'authors');

    }

    public function getImportantArticles() {

        app()->setLocale(request('locale'));

        $importantArticlesList = ArticleList::find(1);

        return $importantArticlesList->articles()
            ->with('category', 'images', 'images.thumbnails')
            ->get();
    }


}
