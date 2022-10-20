<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use LaravelIdea\Helper\App\Models\_IH_Article_C;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // dump();
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
     * @return Category
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
     * @return bool
     */
    public function publish(Request $request,Category $category): bool
    {
        return $category->update([
            'in_menu' => !$category->in_menu
        ]);
    }

    /**
     * @param Category $category
     * @return LengthAwarePaginator
     */
    public function categoryArticles(Category $category): LengthAwarePaginator
    {

        return $category->articles()->orderBy('created_at', 'DESC')->paginate(10);

    }
}
