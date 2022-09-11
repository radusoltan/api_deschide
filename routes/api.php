<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ImageController;
use App\Models\Rendition;
use App\Articles\SearchRepository;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[AuthController::class,'login']);

Route::group(['middleware'=>['auth:sanctum']], function(){

    Route::get('/search', function(SearchRepository $searchRepo){
        dump($searchRepo->search(request('q')));
    });

    Route::post('/logout',[AuthController::class,'logout']);

    // Categories
    Route::apiResource('/categories',CategoryController::class);
    Route::patch('/category/{category}',[CategoryController::class,'update']);
    Route::patch('/category/{category}',[CategoryController::class,'publish']);
    Route::get('/category/{category}/articles',[CategoryController::class,'categoryArticles']);
    Route::post('/category/{category}/add-article',[ArticleController::class,'addByCategory']);

    //Articles
    Route::apiResource('/articles',ArticleController::class);
    Route::get('/article/{article}/related',[ArticleController::class,'getRelatedArticles']);

    Route::get('/article/add',[ArticleController::class,'addMultiple']);

    //Images
    Route::get('/article/{article}/images',[ArticleController::class, 'articleImages']);
    Route::post('/article/{article}/upload-images',[ArticleController::class,'addArticleImages']);
    Route::post('/article/{article}/detach-images',[ArticleController::class,'detachImages']);

    Route::post('/image/set-main',[ImageController::class,'setMainImage']);
    Route::get('/image/{image}/renditions',[ImageController::class,'getRenditions']);

    Route::post('/image/{image}/crop',[ImageController::class,'crop']);
    Route::get('/image/{image}/thumbnails',[ImageController::class,'getImageThumbnails']);
    Route::get('/renditions',function ()
    {
        return Rendition::all();
    });
});
