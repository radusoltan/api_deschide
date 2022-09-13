<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Search\ElasticsearchRepository;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (ElasticsearchRepository $elasticsearch) {
    // $articles = \App\Models\Article::search(request('q'))->get();
    // $response = $elasticsearch->getArticle(Article::find(1));
    $elasticsearch;
    return ['Laravel' => app()->version()];
});

// Route::get('/search', function(ArticlesRepository $articlesRepository){
//     // dump(request()->has('q'));
//     return request()->has('q')
//         ? $articlesRepository->search(request('q'))
//         : App\Models\Article::all();
// });

// require __DIR__.'/auth.php';
