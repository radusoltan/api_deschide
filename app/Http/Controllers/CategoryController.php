<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Repositories\ArticleRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *

     */
    public function index()
    {
        // dump();
//        app()->setLocale(request('locale'));

        return Category::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required','string'],
            'lng' => ['required','string']
        ]);
        app()->setLocale($request->get('lng'));
        return Category::create([
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title')),
            'in_menu' => $request->get('in_menu')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     *
     * @return \App\Models\Category
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return Category
     */
    public function update(Request $request, Category $category): Category
    {
//        dump($request->all());

        app()->setLocale($request->get('lng'));

        $category->update([
            'title' => $request->get('title'),
            'slug' => Str::slug($request->get('title')),
            'in_menu' => $request->get('in_menu')
        ]);
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return bool
     */
    public function destroy(Category $category): bool
    {
        return $category->delete();
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return Category
     */
    public function publish(Request $request,Category $category): Category
    {
        $locale = $request->get('locale');
        app()->setLocale($locale);
        $trans = CategoryTranslation::where([
            ['category_id', $category->getId()],
            ['locale','=',$locale]
        ])->first();
        $trans->update([
            'in_menu' => !$trans->in_menu
        ]);
        return $category->load('translations');
    }

    /**
     * @param Category $category
     *
     */
    public function categoryArticles(
        Category $category,
//        ArticleRepository $repository
    )//: LengthAwarePaginator
    {
        $term = request('term');
        $locale = request('locale');
//        app()->setLocale($locale);
//        if (request()->has('term') && !is_null($term)){
//
//            $searchResults = $repository->searchCategoryArticles($term, $locale, $category);
//
//            return new LengthAwarePaginator(
//                $searchResults->values(),
//                $searchResults->count(),
//                10,
//                request('page'),
//                [
//                    'path' => env('APP_URL').'/category/'.$category->getId().'/search?term='.\request('term').'&page='.\request('page'),
//
//                ]
//            );
//
//        } else {
            return $category->articles()
//                ->translatedIn(request('locale'))
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
//        }

    }
}
