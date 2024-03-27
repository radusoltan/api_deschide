<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Image;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        for ($i=0;$i<=10;$i++){
            $image = Image::make("https://dummyimage.com/1600x900/a8a8a8/fff&text=ImageGallery+$i");
            $image->save(storage_path("app/public/images/imageGallery$i.jpg"));
            $imageFile = new \Illuminate\Http\UploadedFile(
                $image->basePath(),
                $image->basename,
                $image->mime,
                0,
                true
            );
            $service->uploadImage($imageFile);
        }
    }
}
