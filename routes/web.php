<?php

use Illuminate\Support\Facades\Route;
use App\Articles;

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

// Route::get('/search', function(ArticlesRepository $articlesRepository){
//     // dump(request()->has('q'));
//     return request()->has('q')
//         ? $articlesRepository->search(request('q'))
//         : App\Models\Article::all();
// });

// require __DIR__.'/auth.php';
