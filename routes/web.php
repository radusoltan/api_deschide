<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;
use App\Models\Category;
use App\Search\ElasticsearchRepository;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ArticleController;
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

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

//public routes
Route::get('/categories', [CategoryController::class,'getAllPublishedCategories']);
Route::get('/published-articles',[ArticleController::class,'getAllPublishedArticles']);

