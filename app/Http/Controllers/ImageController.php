<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\ArticleImages;
use App\Models\Article;
// use App\Models\Image;
use App\Models\ImageThumbnail;
use App\Models\Rendition;
use App\Services\ImageService;

class ImageController extends Controller
{
    private $service;
    public function __construct(){
        $this->service = new ImageService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        //
    }

    public function setMainImage(Request $request)
    {

        $articleImages = ArticleImages::where('article_id',$request->get('article'))
            ->where('is_main',true)
            ->get();

        if ($articleImages->count() > 0){
            foreach ($articleImages as $image){

                $image->update([
                    'is_main' => false
                ]);
            }
        }

        $mainImage = ArticleImages::where('article_id',$request->get('article'))
            ->where('image_id',$request->get('image'))
            ->first();

        $image = Image::find($request->get('image'));


        $this->service->saveImageThumbnails($image);

        return $mainImage->setMain();
    }

    public function getRenditions(Image $image)
    {
        return ImageThumbnail::where('image_id',$image->getId())
            ->join('renditions','image_thumbnails.rendition_id','=','renditions.id')
            ->get();
    }

    public function crop(Request $request, Image $image)
    {

        $rendition = Rendition::find($request->get('rendition'));
        $crop = $request->get('crop');



        // $img = $image
        //     ->join('image_thumbnails','image_thumbnails.image_id','=','images.id')
        //     ->get()
        // ;

        return $this->service->crop(
            $image,
            $rendition,
            $crop
        );
    }

    public function getImageThumbnails(Image $image)
    {
        return $this->service->getThumbnails($image);



    }
}
