<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    public function article()
    {
        return $this->belongsToMany(Article::class, 'article_galleries','article_id','id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class,'gallery_images','image_id','id');
    }
}
