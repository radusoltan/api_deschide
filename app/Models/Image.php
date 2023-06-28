<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Image extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;

    protected $service;

    //TODO: to add full path to image

    public function __construct()
    {
        parent::__construct();
        $this->service = new ImageService();

    }

    protected $fillable = ['name', 'path', 'width', 'height', 'old_number'];
    public array $translatedAttributes = ['title', 'author', 'description'];

    public function toSearchArray(): array
    {
        return [
            'id' => $this->getId(),
            'translations' => $this->translations()->get(),
        ];
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_images','image_id','id');
    }

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class,'gallery_images','image_id','id');

    }

    public function thumbnails()
    {
        return $this->hasMany(ImageThumbnail::class,'image_id','id');
//        return $this->hasMany(ImageThumbnail::class);
    }

    public function getThumbnails(){

        return ImageThumbnail::where('image_id',$this->id)
            ->join('renditions','image_thumbnails.rendition_id','=','renditions.id')
            // ->join('article_images','image_thumbnails.image_id','=','article_images.image_id')
            // ->select('id')
            ->get();


    }

    public function getArticleMainImage(Article $article){

        $mainImage = ArticleImages::where('article_id',$article->id)
            ->where('is_main',true)
            ->first()
        ;

        if(!$mainImage){
            return ArticleImages::where('article_id',$article->id)->first()->getImageId();
        }

        return $mainImage->getImageId();
    }

    public function setThumbnails()
    {
        $this->service->saveImageThumbnails($this);

    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getPath(){
        return $this->path;
    }

    public function getWidth(){
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getAuthor(){
        return $this->author;
    }


}
