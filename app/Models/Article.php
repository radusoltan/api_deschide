<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Search\Searchable;

class Article extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;

    public $translatedAttributes = ['title', 'slug', 'lead', 'body','status', 'published_at'];
    protected $fillable = ['category_id', 'is_flash', 'is_alert', 'is_breaking'];
    protected $casts = [
        'is_flash' => 'boolean',
        'is_alert' => 'boolean',
        'is_breaking' => 'boolean',
        'related' => 'array'
    ];
    protected $observables = ['flash'];

    public function category(){
      return $this->belongsTo(Category::class);
    }

    public function images(){
        return $this->belongsToMany(Image::class, 'article_images');
    }

    public function related(){
        return $this->belongsToMany($this,'related_articles','article_id','related_id');
    }

    public function gallery()
    {
        return $this->hasOne(Gallery::class);
    }

    public function vzt(){
        return visits($this);
    }

    public static function getPublishedArticles()
    {
        return $this
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where('article_translations.status','=','P');
    }

    public function toSearchArray(): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'translations' => $this->translations(),
            'is_flash'=> $this->is_flash,
            'is_alert' => $this->is_alert,
            'is_breaking' => $this->is_breaking

        ];
    }
}
