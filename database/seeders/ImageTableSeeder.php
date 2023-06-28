<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Image;
use App\Services\ImageService;
use Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Image as ImageManager;

class ImageTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

                    $image->setThumbnails();
                }


            }

        }
    }

    private function importImages(Article $article){
        $response = Http::get('https://deschide.md/api/articles/'.$article->old_number.'/'.app()->getLocale().'/images.json?items_per_page=100&language=');
        $items = $response->json();

        foreach ($items['items'] as $oldImage){

            $destinationPath = storage_path('app/public/images/'.$oldImage['basename']);

            $image = Image::where('old_number', $oldImage['id'])->first();
            if (!$image){
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
//            $image->setThumbnails();
            if (!$article->images->contains($image)) {
                $article->images()->attach($image);
            }
        }
    }
}
