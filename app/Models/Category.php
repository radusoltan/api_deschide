<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes = ['title','slug'];
    protected $fillable = ['in_menu'];
    protected $casts = [
        'in_menu' => 'boolean'
    ];

    public function articles(){
        return $this->hasMany(Article::class);
    }
}
