<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleList;
use App\Models\Author;
use App\Repositories\ArticleRepository;
use Elastic\Elasticsearch\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Services\ImageService;
use App\Models\Category;
use App\Models\ArticleImages;
use App\Search;
use App\Models\ArticleTranslation;
use Carbon\Carbon;
use LaravelIdea\Helper\App\Models\_IH_Article_C;
use LaravelIdea\Helper\App\Models\_IH_Author_C;

class ArticleController extends Controller
{
    private $service;
    private $elastic;

    public function __construct(Client $client)
    {
        $this->service = new ImageService();
        $this->elastic = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Article[]|Collection|_IH_Article_C
     */
    public function index()
    {
        return Article::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return Article
     */
    public function show(Article $article)
    {

//        app()->setLocale(request('locale'));
        $article->vzt()->increment();
        return $article->load('visits','images', 'authors', 'lists');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Article $article
     * @return Article
     */
    public function update(Request $request, Article $article)
    {
        app()->setLocale($request->get('lng'));

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'lead' => $request->lead,
            'body' => $request->body,
            'is_breaking' => $request->is_breaking,
            'is_alert' => $request->is_alert,
            'is_flash' => $request->is_flash,
            'status' => $request->status
        ]);

        if ($article->status === 'P'){
            $article->elasticsearchUpdate($this->elastic);
        }

        return $article;
    }

//    public function updateIsFlash(Article $article){
//
//        return $article->setIsFlash();
//    }
//
//    public function updateIsAlert(Article $article){
//
//        return $article->setIsAlert();
//    }
//
//    public function updateIsBreaking(Article $article){
//
//        return $article->setIsBreaking();
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return Response
     */
    public function destroy(Article $article)
    {
        //
    }

    public function addByCategory(Request $request, Category $category)
    {
        $request->validate([
            'lng' => ['required', 'string'],
            'title' => ['required', 'string']
        ]);



        $locale = $request->get('lng');
        $title = $request->get('title');
        $slug = Str::slug($title);
        // $keywords = $request->get('keywords');

        try {
            app()->setLocale($locale);
            $article = $category->articles()->create([
                'title' => $title,
                'slug' => $slug,
                'category_id' => $category->id,
            ]);
            // dump();
            return $article;
        } catch (\Exception $e) {
            if ($e->errorInfo[1] === 1062) {
                return response()->json('Acest titlu exista deja');
            }
        }
    }

    public function articleImages(Article $article)
    {

        $resp = [];
//
        foreach ($article->images()->get() as $image) {

            $resp[] = [
                'id' => $image->getId(),
                'name' => $image->getName(),
                'path' => $image->getPath() . '/' . $image->getName(),
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
                'isMain' => $image->getArticleMainImage($article) === $image->getId() ?? null,
                'thumbs' => $image->getThumbnails(),
                'translations' => $image->translations()->get()
            ];
        }
//        dump($resp);
        return $resp;
//        if ($article->images()->count()>0){
//            return $resp;
//        } else {
//            return [];
//        }
//        return $resp;
    }

    public function addArticleImages(Request $request, Article $article)
    {
//        dump($request);

        foreach ($request->images as $file) {

            $image = $this->service->uploadImage($file);

            if (!$article->images->contains($image)) {
                $article->images()->attach($image);
            }
        }

        if ($article->images()->count() === 1) {

            $image = $article->images()->first();

            $mainImage = ArticleImages::where('article_id', $article->id)
                ->where('image_id', $image->id)
                ->first();

            $mainImage->setMain();

            $this->service->saveImageThumbnails($image);
        }

        return $article->images()->get();
    }

    public function attachImages(Request $request, Article $article)
    {

        $article->images()->sync($request->get('ids'));

        return $article->images()->get();
    }

    /**
     * @param Request $request
     * @param Article $article
     *
     * @return Collection
     *
    */
    public function detachImages(Request $request, Article $article): Collection
    {
        $article->images()->detach($request->get('id'));
        return $article->images()->get();
    }



    /**
     * @param Article $article
     * @return Collection
     */
    public function getRelatedArticles(Article $article): Collection
    {

        return $article->related()->get();
    }

    /**
     * @param Article $article
     * @return Collection
     */
    public function addRelated(Article $article): Collection
    {
        $article->related()->sync(request('related'));

        return $article->related()->get();
    }

    public function relatedDetach(Article $article)
    {
        $article->related()->detach(request('id'));
        return $article->related()->get();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getPublishedArticles()//: LengthAwarePaginator
    {
        return Article::query()
            ->join('article_translations', 'article_translations.article_id', '=', 'articles.id')
            ->where('article_translations.status', '=', 'P')
            ->orderBy('articles.created_at', 'DESC')
            ->paginate();
    }

    /**
     * @param Category $category
     * @return LengthAwarePaginator
     */
    public function getPublishedArticlesByCategory(Category $category): LengthAwarePaginator
    {
        return Article::query()
            ->join('article_translations', 'article_translations.article_id', '=', 'articles.id')
            ->where('article_translations.status', '=', 'P')
            ->where('articles.category_id', $category->id)
            ->orderBy('articles.created_at', 'DESC')
            ->paginate();
    }

    /**
     * @param Search\Article\ArticleRepository $repo
     * @return Collection
     */
    public function search(Search\Article\ArticleRepository $repo): Collection
    {

        if (request()->has('locale')) {
            app()->setLocale(request('locale'));
        }

        $locale = app()->getLocale();

        $q = request('q');

        // dd();

        return $repo->search($q, $locale);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return Article
     */
    public function setArticlePublishTime(Request $request, Article $article): Article
    {
        app()->setLocale($request->get('locale'));
        $dt = Carbon::parse($request->get('time'));
        $translation = ArticleTranslation::where('locale', $request->get('locale'))
            ->where('article_id', $article->id)
            ->first();
        $translation->publish_at = $dt;
        $translation->save();

        return $article;
    }

    /**
     * @param $id
     * @return Article
     */
    public function deleteTranslationEvent($id): Article
    {
        $translation = ArticleTranslation::find($id);
        $translation->publish_at = null;
        $translation->save();
        return Article::find($translation->article_id);
    }

    /**
     * @param Article $article
     * @return Collection
     */
    public function getAuthors(Article $article): Collection
    {
        return $article->authors()->get();
    }


}
