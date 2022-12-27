<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;

    public $translatedAttributes = ['in_menu','title','slug'];
    protected $fillable = ['old_number'];
    protected $casts = [
        'in_menu' => 'boolean'
    ];

    /**
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @return string
     */
    public function getSearchIndex(): string
    {
        return $this->getTable();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function toSearchArray(): array
    {
        return [
            'id' => $this->getId(),
            'translations' => $this->translations()->get()
        ];
    }

    public function getPublishedArticles(){
        return $this->articles()
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where('article_translations.status','=','P')
            ->paginate(10);
    }
}
