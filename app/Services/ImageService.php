<?php

namespace App\Services;

use Image as ImageManager;
use App\Models\Image;
use App\Models\Rendition;
use Illuminate\Http\UploadedFile;
use App\Models\ImageThumbnail;

class ImageService {

    public function uploadImage(UploadedFile $file)
    {
        $name = $file->getClientOriginalName();

        $imageFile = ImageManager::make($file->getRealPath());

        $destinationPath = storage_path('app/public/images/'.$name);

//        dd();

        $imageFile->save($destinationPath,100,'jpg');

        $image = Image::where('name', $name)->first();

        if (!$image){
            $image = Image::create([
                'name' => $name,
                'path' => 'storage/images',
                'width' => $imageFile->width(),
                'height' => $imageFile->height()
            ]);
        }

        $this->saveImageThumbnails($image);

        return $image;
    }

    public function saveImageThumbnails(Image $image){

        $file = file_get_contents(public_path($image->path.'/'.$image->name));

//        dd($file);

        $name = $image->name;
        // dump($name);
        $renditions = Rendition::all();
        foreach ($renditions as $rendition){
            $img = ImageManager::make($file);
            $destinationPath = public_path('storage/images/thumbnails/');

            $thumb = ImageThumbnail::where('rendition_id',$rendition->id)
                ->where('image_id',$image->id)
                ->first();

            $cropped = $img->crop($rendition->width,$rendition->height)
                ->save($destinationPath.$rendition->name.'_'.$name,100,'jpg');
            if (!$thumb){
                ImageThumbnail::create([
                    'image_id' => $image->id,
                    'rendition_id' => $rendition->id,
                    'width' => $cropped->width(),
                    'height' => $cropped->height(),
                    'path' => 'storage/images/thumbnails/'.$rendition->name.'_'.$name
                ]);
            }
        }

    }

    public function crop(Image $image, Rendition $rendition,array $crop)
    {
        $name = $image->name;
        $destinationPath = public_path('storage/images/thumbnails/');
        $img = ImageManager::make(public_path('storage/images/').$image->name);

        $width  = round($image->width / 100 * $crop['p']['width']);
        $height = round($image->height / 100 * $crop['p']['height']);
        $x      = round($image->width / 100 * $crop['p']['x']);
        $y      = round($image->height / 100 * $crop['p']['y']);

        $cropped = $img->crop( $width, $height, $x, $y )->fit($rendition->width,$rendition->height)
            ->save($destinationPath.$rendition->name.'_'.$name,100,'jpg');

        $thumb = ImageThumbnail::where([
            ['image_id',$image->id],
            ['rendition_id',$rendition->id]
        ])->first();

        $thumb->update([
            'image_id' => $image->id,
            'rendition_id' => $rendition->id,
            'width' => $cropped->width(),
            'height' => $cropped->height(),
            'path' => 'storage/images/thumbnails/'.$rendition->name.'_'.$name,
            'coords' => json_encode($crop['c'])
        ]);

        if (!$thumb){
            $thumb =ImageThumbnail::create([
                'image_id' => $image->id,
                'rendition_id' => $rendition->id,
                'width' => $cropped->width(),
                'height' => $cropped->height(),
                'path' => 'storage/images/thumbnails/'.$rendition->name.'_'.$name,
                'coords' => json_encode($crop)
            ]);
        }
        return $thumb;
    }

    public function getThumbnails(Image $image)
    {
        return ImageThumbnail::where('image_id', $image->getId())->with('rendition')
            // ->join('renditions','image_thumbnails.rendition_id','=','renditions.id')
            ->get();
    }

}
