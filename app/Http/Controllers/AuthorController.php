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
     *
     */
    public function index()//: Collection
    {
        return Author::paginate(10);
    }

    public function getAllAuthors() {
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
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
//            'email' => 'required|string|email|unique:authors,email',
            'locale' => 'required'
        ]);
        app()->setLocale($request->get('locale'));

        $author = Author::create([
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'full_name' => $request->get('first_name').' '.$request->get('last_name'),
            'slug' => \Str::slug($request->get('first_name').' '.$request->get('last_name'))
        ]);

        return $author;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     *
     * @return \App\Models\Author
     */
    public function show(Author $author)
    {
        return $author;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     *
     *
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
     *
     * @return \App\Models\Author
     */
    public function update(Request $request, Author $author)
    {
        app()->setLocale($request->get('locale'));

        $author->update([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'full_name' => $request->get('first_name').' '.$request->get('last_name'),
            'email' => $request->get('email'),
            'facebook' => $request->get('facebook'),
            'slug' => \Str::slug($request->get('first_name').' '.$request->get('last_name'))
        ]);
        return $author;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     *
     * @return bool
     */
    public function destroy(Author $author)
    {
        return $author->delete();
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
                'email' => $request->get('email'),
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
