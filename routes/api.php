<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\AuthorController;
use App\Models\Rendition;
use App\Search\ElasticsearchRepository;

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

Route::get('published',[ArticleController::class,'getPublishedArticles']);
Route::get('/published/{category}',[ArticleController::class,'getPublishedArticlesByCategory']);

Route::group(['middleware'=>['auth:sanctum']], function(){



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
    Route::post('/article/{article}/related-add',[ArticleController::class,'addRelated']);
    Route::post('/article/{article}/related-detach',[ArticleController::class,'relatedDetach']);
    Route::get('/article/add',[ArticleController::class,'addMultiple']);
    Route::post('/articles/search', [ArticleController::class,'search']);
//    Route::get('/article/{article}/authors', [ArticleController::class, 'getAuthors']);

    //Article set publish time
    Route::post('/article/{article}/publish-time',[ArticleController::class,'setArticlePublishTime']);
    Route::delete('/translation/{id}/delete-event',[ArticleController::class,'deleteTranslationEvent']);


    //USERS
    Route::apiResource('/users', UserController::class);

    //ROLES
    Route::apiResource('/roles', RolesController::class);

    //PERMISSIONS
    Route::apiResource('/permissions', PermissionsController::class);

    //AUTHORS
    Route::get('/article/{article}/authors',[AuthorController::class, 'getArticleAuthors']);
    Route::post('/article/{article}/authors',[AuthorController::class, 'addArticleAuthors']);
    Route::post('/authors/search', [AuthorController::class ,'search']);



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

    Route::get('/post',[\App\Http\Controllers\FacebookController::class, 'postNews']);

    Route::get('/import',[\App\Http\Controllers\FacebookController::class, 'RssArticles']);

});
