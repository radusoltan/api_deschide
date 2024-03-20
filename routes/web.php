<?php

use App\Http\Controllers\FacebookSocialiteController;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ArticleController;
use Shieldon\Firewall\Panel;
use Illuminate\Http\Request;

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


Route::get('test', function (){

    $service = new \App\Services\ImageService();

    $image = Image::make('https://dummyimage.com/1600x900/a8a8a8/fff&text=Main+1600x900');
    $image->save(storage_path('app/public/images/main.jpg'));
    $imageFile = new \Illuminate\Http\UploadedFile(
        $image->basePath(),
        $image->basename,
        $image->mime,
        0,
        true
    );
    $service->uploadImage($imageFile);
//    dump($imageFile->getRealPath());

});

Route::get('last-published',[ArticleController::class,'getLastPublishedArticles']);
Route::get('article/{article}',[ArticleController::class,'getArticleById']);
Route::get('category/{slug}', [CategoryController::class,'getCategory']);
Route::get('categories',function (){
    return \App\Models\Category::all();
});

Route::get('/',function(\Elastic\Elasticsearch\Client $client){
    $locale = request()->get('locale');
//    dump('here');

//    $params = [
//        'index' => 'articles',
//        'type' => '_doc',
//        'size' => 10,
//        'body'   => [
//            'query' => [
//                'match' => [
//                    "translations.locale" => $locale
//                ]
//            ],
//            'sort' => [
//                'created_at' => [
//                    'order' => 'desc',
//                    'unmapped_type' => 'date', // Optional, if the field type is not explicitly mapped
//                ],
//            ],
//        ]
//    ];
//
//    $response = $client->search($params)->asArray();
//    $obj = $client->search($params)->asObject();
//
//    $ids = Arr::pluck($response['hits']['hits'],['_id']);
//
//    $articles = \App\Models\Article::findMany($ids)
//        ->sortBy(function ($article) use ($ids) {
//            return array_search($article->getKey(), $ids);
//        });
//
//
//    return response()->json($articles);
});



//RSS
//Route::feeds();
Route::get('/rss',[\App\Http\Controllers\RssReaderController::class,'readRss']);

// FACEBOOK
