<?php

use App\Http\Controllers\FacebookSocialiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ArticleController;
use Shieldon\Firewall\Panel;

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
//    phpinfo();
    return ['Laravel' => app()->version()];
});

Route::any('/firewall/panel/{path?}', function() {

    $panel = new Panel();
    $panel->csrf(['_token' => csrf_token()]);
    $panel->entry();

})->where('path', '(.*)');

//public routes
Route::get('/categories', [CategoryController::class,'getAllPublishedCategories'])->middleware('firewall');
//Route::get('/category/{slug}',[CategoryController::class,'getCategory']);
Route::get('/published-articles',[ArticleController::class,'getAllPublishedArticles']);

//RSS
//Route::feeds();
Route::get('/rss',[\App\Http\Controllers\RssReaderController::class,'readRss']);

// FACEBOOK
Route::group(['prefix'=> 'login/facebook'], function (){
    Route::get('/',[\App\Http\Controllers\FacebookController::class,'FacebookLogin'])->name('facebook-login');
    Route::get('/callback',[\App\Http\Controllers\FacebookController::class,'handleProviderFacebookCallback'])->name('facebook-callback');
});
//Route::get('/post',[\App\Http\Controllers\FacebookController::class, 'postNews']);

Route::get('/import',[\App\Http\Controllers\FacebookController::class, 'RssArticles']);
//Route::get('/fb-user',[\App\Http\Controllers\GraphController::class,'retrieveUserProfile']);
//Route::get('/fb-logout', [\App\Http\Controllers\GraphController::class,'deauthorize']);
//social media routes
Route::get('auth/facebook', [FacebookSocialiteController::class, 'redirectToFB']);
Route::get('login/facebook/callback', [FacebookSocialiteController::class, 'handleCallback']);
