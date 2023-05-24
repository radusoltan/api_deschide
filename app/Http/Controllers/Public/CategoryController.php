<?php

namespace App\Http\Controllers\Public;

use App\Models\Article;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    public function getAllPublishedCategories()
    {
        Log::info('cats');
        $res = [];
        $locale = request('locale');
//////
         app()->setLocale($locale);

         $categories = Category::select(['category_id','title','slug','in_menu'])
             ->join('category_translations','category_translations.category_id','=', 'categories.id')
             ->where([
                 ['in_menu', true],
                 ['category_translations.locale','=', $locale]

             ])->get();
//        dump($categories);
         foreach ($categories as $category){
            $res[] = [
                'name'=>$category->title,
                'slug'  => $category->slug
            ];
         }
//        dump($res);
         return $res;

    }

    public function getCategory($slug)
    {
        $translation = CategoryTranslation::where('slug', $slug)->where('locale', request('locale'))->first();
        $category = Category::find($translation->category_id);

        $lastArticles = [];

        app()->setLocale(request('locale'));
        $articles = Article::query()
            ->select(['is_flash', 'is_alert', 'is_breaking', 'updated_at', 'title', 'lead','articles.id'])
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where('article_translations.status','P')
            ->where('article_translations.locale',request('locale'))
            ->orderBy('article_translations.published_at','DESC')
            ->limit(30)
            ->get();

//        $popularArticles = visits(Article::class)->top(10);

        foreach ($articles as $article){
//            dump($article->visits()->get());
            $lastArticles[] = [
                'title' => $article->title,
                'slug' => $article->slug,
                'lead' => $article->lead,
                'category' => $category,
                'images' => $article->images()->with('thumbnails')->get(),
                'visits' => visits($article)->count()
            ];
        }

        return [
            'last_articles' => $lastArticles,
//            'popular' => $popularArticles
        ];


    }

    public function getCategoryPublishedArticles(Category $category)
    {

        return $category->getPublishedArticles(); //Article::findMany($ids);


    }



}
