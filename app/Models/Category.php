<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Search\Searchable;

class Category extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;
    public $translatedAttributes = ['title','slug'];
    protected $fillable = ['in_menu'];
    protected $casts = [
        'in_menu' => 'boolean'
    ];

    public function articles(){
        return $this->hasMany(Article::class);
    }

    public function getSearchIndex(){
        return $this->getTable();
    }

    public function toSearchArray(): array
    {
        if ($this->in_menu){
            return [
                'id' => $this->id,
                'translations' => $this->translations()->get()
            ];
        } else {
            return [];
        }
    }
}
