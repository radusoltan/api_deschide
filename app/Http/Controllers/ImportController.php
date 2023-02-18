<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Image;
use App\Services\ImageService;
use Carbon\Carbon;
use Http;
use Str;
use Image as ImageManager;

class ImportController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ImageService();
    }

    public function importCategories()
    {

//        foreach (config('translatable.locales') as $locale){
//
//
//            $response = Http::get('https://deschide.md/api/sections?items_per_page=20&language='.$locale);
//            $apiSections = $response->json();
//            foreach ($apiSections['items'] as $item){
//
//
//                $category = Category::where('old_number',$item['number'])->first();
//                if (!$category){
//
//                    $category = Category::create([
//                        'title' => ucfirst($item['title']),
//                        'slug' => Str::slug($item['title']),
//                        'old_number' => $item['number'],
//                        'in_menu' => true
//                    ]);
//                }
//
//                if(!$category->hasTranslation($locale) && $item['number']===$category->old_number){
//                    app()->setLocale($locale);
//                    $category->update([
//                        'title' => ucfirst($item['title']),
//                        'slug' => Str::slug($item['title'])
//                    ]);
//                }
//
//            }
//
//        }

    }

    public function importArticles()
    {
        foreach (config('translatable.locales') as $locale){
            app()->setLocale($locale);

            $response = Http::get('https://deschide.md/api/articles?items_per_page=5&language='.app()->getLocale());
            $items = $response->json();

            foreach ($items['items'] as $item){
                $category = Category::where('old_number', $item["section"]["number"])->first();

                $article = Article::where('old_number',$item['number'])->first();

                $publish_at_date = Carbon::parse($item['published']);

                if (!$article){
                    if (isset($item['fields']['Continut'])){
                        $article = Article::create([
                            'category_id' => $category->getId(),
                            'title' => $item['title'],
                            'slug' => Str::slug($item['title']),
                            'lead' => $item['fields']['lead'] ?? '',
                            'body' => $item['fields']['Continut'],
                            'status' => $item['status']==='Y' ? 'P' : 'another',
                            'old_number' => $item['number'],
                            'published_at' => $publish_at_date
                        ]);
                    }
                }

                $this->importImages($article);
                $this->importAuthors($article);
//                $this->importImage($item['renditions'][0]['details']);

            }
        }

    }

    public function importImages(){
        $articles = Article::all();

        $locale = 'ro';

        foreach ($articles as $article){

            $response = Http::get('https://deschide.md/api/articles/'.$article->old_number.'/'.$locale.'/images.json?items_per_page=100');
            $items = $response->json();
            if (isset($items['items'])) {

                foreach ($items['items'] as $oldImage){
                    $destinationPath = storage_path('app/public/images/'.$oldImage['basename']);

                    $image = Image::where('old_number',$oldImage['id'])->first();

                    if (!$image) {
                        $image_to_import = ImageManager::make("https://deschide.md/images/".$oldImage['basename']);
                        $image_to_import->save($destinationPath,100,'jpg');
                        $image = Image::create([
                            'name' => $oldImage['basename'],
                            'path' => 'storage/images',
                            'width' => $image_to_import->width(),
                            'height' => $image_to_import->height(),
                            'old_number' => $oldImage['id']
                        ]);

                    }

                    if (!$article->images->contains($image)){
                        $article->images()->attach($image);
                    }

                    $this->service->saveImageThumbnails($image);


                }


            } else {
//                dump($items);
            }

//            foreach ($items['items'] as $oldImage){
//
//                $destinationPath = storage_path('app/public/images/'.$oldImage['basename']);
//
//                $image = Image::where([
//                    ['old_number','=', $oldImage['id']],
//                    ['name','=',$oldImage['basename']]
//                ])->toSql();
//                dump($image);
////                if (!$image){
////                    $image_to_import = ImageManager::make("https://deschide.md/images/".$oldImage['basename']);
////                    $image_to_import->save($destinationPath,100,'jpg');
////                    $image = Image::create([
////                        'name' => $oldImage['basename'],
////                        'path' => 'storage/images',
////                        'width' => $image_to_import->width(),
////                        'height' => $image_to_import->height(),
////                        'old_number' => $oldImage['id']
////                    ]);
////
////                }
////
////                $this->service->saveImageThumbnails($image);
////                if (!$article->images->contains($image)) {
////                    $article->images()->attach($image);
////                }
//            }

        }

    }

    private function importAuthors(Article $article)
    {

        foreach (config('translatable.locales') as $locale){

            app()->setLocale($locale);

            $response = Http::get('https://deschide.md/api/authors/article/'.$article->old_number.'/'.app()->getLocale().'.json');
            // /api/authors/article/{number}/{language}.{_format}
            // GET /api/articles/{number}/{language}/authors.{_format}
            $items = $response->json();
//            dump($items);
            if (isset($items['items'])){
                foreach($items['items'] as $apiAuthor) {

                    $author = Author::where('old_number', $apiAuthor['author']['id'])->first();

                    if (!$author) {

                        $author = Author::create([
                            'first_name' => $apiAuthor['author']['firstName'],
                            'last_name' => $apiAuthor['author']['lastName'],
                            'full_name' => $apiAuthor['author']['firstName'].' '.$apiAuthor['author']['lastName'],
                            'slug' => \Str::slug($apiAuthor['author']['firstName'].' '.$apiAuthor['author']['lastName']),
                            'old_number' => $apiAuthor['author']['id']
                        ]);
                    }

                    if(!$article->authors->contains($author)) {
                        $article->authors()->attach($author);
                    }

                }
            }

        }


    }


}
