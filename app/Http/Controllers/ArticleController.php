<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\ImageService;
use App\Models\Category;
use App\Models\ArticleImages;

class ArticleController extends Controller
{
    private $service;

    public function __construct(){
        $this->service = new ImageService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Article::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return $article;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        app()->setLocale($request->lng);

        $article->update([
            'title' => $request->title,
            'lead' => $request->lead,
            'body' => $request->body,
            'is_breaking' => $request->is_breaking,
            'is_alert' => $request->is_alert,
            'is_flash' => $request->is_flash,
            'status' => $request->status
        ]);

        return $article;


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }

    public function addByCategory(Request $request, Category $category)
    {
        $request->validate([
            'lng' => ['required', 'string'],
            'title' => ['required','string']
        ]);
        app()->setLocale($request->get('lng'));
        return $category->articles()->create([
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title')),
            'category_id' => $category->id
        ]);

    }

    public function articleImages(Article $article){
        $resp = [];

        foreach ($article->images()->get() as $image){

            $resp[]= [
                'id'=> $image->getId(),
                'name' => $image->getName(),
                'path' => $image->getPath().'/'.$image->getName(),
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
                'isMain' => $image->getArticleMainImage($article)===$image->getId() ?? null,
                'thumbs' => $image->getThumbnails(),
            ];
        }

        // return $resp;
        // dump($resp);
        return $resp;
    }

    public function addArticleImages(Request $request, Article $article){



        foreach ($request->images as $file){

            $image = $this->service->uploadImage($file);

            if (!$article->images->contains($image)){
                $article->images()->attach($image);
            }
        }



        if ($article->images->count() ===1){
            $image = $article->images()->first();

            $mainImage = ArticleImages::where('article_id',$article->id)
            ->where('image_id',$image->id)
            ->first();

            $mainImage->setMain();

            $this->service->saveImageThumbnails($image);

        }

        return $article->images()->get();

    }

    public function detachImages(Request $request,Article $article)
    {
        $article->images()->detach($request->get('id'));
        return $article->images()->get();
    }

    public function getRelatedArticles(Article $article){

        return $article->related()->get();

    }

    public function getPublishedArticles()
    {
        return Article::query()
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where('article_translations.status','=','P')
            ->orderBy('articles.created_at','DESC')
            ->paginate();
    }

    public function getPublishedArticlesByCategory(Category $category)
    {
        return Article::query()
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where('article_translations.status','=','P')
            ->where('articles.category_id', $category->id)
            ->orderBy('articles.created_at','DESC')
            ->paginate();
    }
}
