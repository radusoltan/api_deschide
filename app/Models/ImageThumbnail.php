<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class ImageThumbnail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['image_id','rendition_id','path','coords', 'width', 'height'];

    protected $casts = [

        'coords' => 'json'
    ];

    public function image(){
        return $this->belongsTo(Image::class,'image_id','id');
    }

    public function rendition()
    {
        return $this->belongsTo(Rendition::class,'rendition_id', 'id');

    }
}
