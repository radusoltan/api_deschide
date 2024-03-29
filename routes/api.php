<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FacebookSocialiteController;
use App\Models\Article;
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

    return $request->user()->load('permissions', 'roles.permissions');

});

Route::post('login',[AuthenticatedSessionController::class,'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::group(['prefix' => 'public'], function (){
    Route::get('home',[\App\Http\Controllers\Public\HomepageController::class,'getInitialProps']);
    Route::get('important-articles',[\App\Http\Controllers\Public\ArticleController::class,'getImportantArticles']);
    Route::get('categories', [\App\Http\Controllers\Public\CategoryController::class, 'getAllPublishedCategories']);
    Route::get('category/{slug}',[\App\Http\Controllers\Public\CategoryController::class,'getCategory']);
    Route::get('category/{category}/articles',[\App\Http\Controllers\Public\CategoryController::class,'getCategoryPublishedArticles']);
    Route::get('articles',[\App\Http\Controllers\Public\ArticleController::class,'getAllPublishedArticles']);
    Route::get('article/{article}',[\App\Http\Controllers\Public\ArticleController::class,'getArticleById']);
//    Route::get('/articleById/{article}',[\App\Http\Controllers\Public\ArticleController::class,'getArticleById']);
});



Route::group(['middleware'=>['auth:sanctum']], function(){



//    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/check-auth',[AuthController::class,'checkAuth']);

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

    Route::patch('/articles/{article}/flash', function (Article $article){
        return $article->setIsFlash();
    });
    Route::patch('/articles/{article}/alert', function (Article $article){
        return $article->setIsAlert();
    });
    Route::patch('/articles/{article}/breaking', function (Article $article){
        return $article->setIsBreaking();
    });
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
    Route::apiResource('/authors', AuthorController::class);
    Route::get('/all-authors',[AuthorController::class, 'getAllAuthors']);
    Route::get('/article/{article}/authors',[AuthorController::class, 'getArticleAuthors']);
    Route::post('/article/{article}/authors',[AuthorController::class, 'addArticleAuthors']);
    Route::post('/authors/search', [AuthorController::class ,'search']);
    Route::delete('/article/{article}/authors/{author}',[AuthorController::class, 'deleteArticleAuthor']);



    //Images
    Route::get('/article/{article}/images',[ArticleController::class, 'articleImages']);
    Route::post('/article/{article}/upload-images',[ArticleController::class,'addArticleImages']);
    Route::post('/article/{article}/detach-images',[ArticleController::class,'detachImages']);
    Route::post('/article/{article}/attach-images',[ArticleController::class,'attachImages']);

    Route::get('/images',[ImageController::class,'index']);
    Route::get('/images/{image}',[ImageController::class, 'show']);


    Route::post('/image/set-main',[ImageController::class,'setMainImage']);
    Route::get('/image/{image}/renditions',[ImageController::class,'getRenditions']);

    Route::patch('/image/{image}/meta',[ImageController::class,'addImageMeta']);
    Route::patch('/image/{image}/edit-meta',[ImageController::class,'editMeta']);

    Route::post('/image/{image}/crop',[ImageController::class,'crop']);
    Route::get('/image/{image}/thumbnails',[ImageController::class,'getImageThumbnails']);
    Route::get('/renditions',function ()
    {
        return Rendition::all();
    });

    Route::get('import/categories',[\App\Http\Controllers\ImportController::class,'importCategories']);
    Route::get('import/articles',[\App\Http\Controllers\ImportController::class,'importArticles']);

    Route::get('/post',[\App\Http\Controllers\FacebookController::class, 'postNews']);

    Route::get('/import',[\App\Http\Controllers\FacebookController::class, 'RssArticles']);

    // Important articles list
    Route::get('/lists',[\App\Http\Controllers\ArticlesListController::class, 'getLists']);
    Route::get('/lists/{list}',[\App\Http\Controllers\ArticlesListController::class, 'getList']);
    Route::post('/lists',[\App\Http\Controllers\ArticlesListController::class, 'createArticlesList']);
    Route::post('/list/{articleList}/attach',[\App\Http\Controllers\ArticlesListController::class, 'addArticlesToList']);
    Route::post('/list/{articleList}/detach',[\App\Http\Controllers\ArticlesListController::class,'detachArticleFromList']);
    Route::post('/lists/{list}/reorder',[\App\Http\Controllers\ArticlesListController::class,'reorderArticles']);
    Route::get('/lists/{article}/article',[\App\Http\Controllers\ArticlesListController::class,'articleLists']);

    // ElasticSearch
    Route::get('/elastic',[\App\Http\Controllers\ElasticSearchController::class,'getIndexes']);

});




