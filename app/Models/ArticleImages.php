<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImages extends Model
{
    use HasFactory;
    protected $fillable = ['is_main'];
    public $timestamps = false;

    public function articles(){
        return $this->belongsToMany(Article::class,'article_images');
    }

    public function images(){
        return $this->belongsToMany(Image::class,'article_images');
    }

    public function getImageId(){
        return $this->image_id;
    }

    public function setMain(){
        $this->is_main = true;
        $this->save();
        return $this;
    }
}
