<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleList;
use Illuminate\Http\Request;

class ArticlesListController extends Controller
{

    public function getLists() {
        return ArticleList::with('articles')->get();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function createArticlesList(Request $request) {

        return ArticleList::create([
            'name' => $request->get('name'),
            'max_item_count' => $request->get('max_item_count')
        ]);

    }

    public function addArticlesToList(Request $request, ArticleList $articleList) {

        $article = Article::find($request->get('article'));

        if (!$articleList->articles->contains($article)){
            $articleList->articles()->attach($article);
        }

        $articleList->setCount();



        return $articleList->load('articles');

    }

    public function detachArticleFromList(Request $request, ArticleList $articleList) {

        $article = Article::find($request->get('article'));
        $articleList->articles()->detach($article);
        $articleList->setCount();
        return $articleList->load('articles');


    }
}
