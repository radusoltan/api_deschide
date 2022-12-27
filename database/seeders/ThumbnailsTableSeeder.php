<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThumbnailsTableSeeder extends Seeder
{
    public $service;

    public function __constructor()
    {
        $this->service = new ImageService();

    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $images = Image::all();

        foreach ($images as $image){
            $this->service->saveImageThumbnails($image);
        }
    }
}
