<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleList;
use App\Models\FeaturedArticlesList;
use Illuminate\Http\Request;

class ArticlesListController extends Controller
{

    public function getLists() {
        return ArticleList::with('articles')->get();
    }

    public function getList(ArticleList $list) {

        app()->setLocale('ro');
//        dd($list);

        return $list->featured()->with('article')->get();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function createArticlesList(Request $request) {

        return ArticleList::create([
            'name' => $request->get('name'),
            'max_item_count' => $request->get('max_item_count'),
        ]);

    }

    public function addArticlesToList(Request $request, ArticleList $articleList) {

        if ($articleList->count <= $articleList->maix_item_count) {
            $article = Article::find($request->get('article'));

            if (!$articleList->articles->contains($article)){
                $articleList->articles()->attach($article);
            }

            $articleList->setCount();
        }

        return $articleList->load('articles');

    }

    public function detachArticleFromList(Request $request, ArticleList $articleList) {

        $article = Article::find($request->get('article'));
        $articleList->articles()->detach($article);
        $articleList->setCount();
        return $articleList->load('articles');


    }

    public function reorderArticles(Request $request, ArticleList $list){

        foreach ($request->all() as $item){
            $featuredItem = FeaturedArticlesList::find($item['id']);

            $featuredItem->update([
                'order' => $item['order']
            ]);
        }

        return $list->load('articles', 'featured');

    }

    public function articleLists(Article $article) {
        return $article->lists()->get();
    }
}
