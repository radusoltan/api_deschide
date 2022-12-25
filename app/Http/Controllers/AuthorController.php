<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Article;
use App\Models\AuthorTranslation;
use App\Repositories\AuthorRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Search;
use LaravelIdea\Helper\App\Models\_IH_Author_C;
use LaravelIdea\Helper\App\Models\_IH_AuthorTranslation_C;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return _IH_Author_C|Author[]|Collection
     */
    public function index(): Collection
    {
        return Author::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        //
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return Author[]|Collection|_IH_Author_C
     */
    public function addArticleAuthors(Request $request,Article $article)
    {

        if ($request->has('author')){

            $author = Author::find($request->get('author'));
            if (!$article->authors->contains($author->getId())) {
                $article->authors()->attach($author);
            }
        } else {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'locale' => 'required'
            ]);
            app()->setLocale($request->get('locale'));

            $author = Author::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'full_name' => $request->get('first_name').' '.$request->get('last_name'),
                'slug' => \Str::slug($request->get('first_name').' '.$request->get('last_name'))
            ]);

            $article->authors()->attach($author->getId());
        }


        return $article->authors()->get();

    }

    /**
     * @param Article $article
     * @return Collection
     */
    public function getArticleAuthors(Article $article): Collection
    {
        return $article->authors()->get();
    }

    /**
     * @param AuthorRepository $repo
     * @return Collection
     */
    public function search(AuthorRepository $repo): Collection
    {
        if (request()->has('locale')) {
            app()->setLocale(request('locale'));
        }

        $locale = app()->getLocale();

        $q = request('q');

        return $repo->search($q, $locale);

    }

    public function deleteArticleAuthor(Article $article, Author $author)
    {
        $article->authors()->detach($author->getId());

        return $article->authors()->get();
    }
}
