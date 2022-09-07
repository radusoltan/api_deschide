<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'path', 'width', 'height'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_images','article_id','image_id');
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
