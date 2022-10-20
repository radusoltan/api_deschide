<?php

namespace App\Models;

use Awssat\Visits\Visits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Search\Searchable;

class Article extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;
    use SoftDeletes;


    public $translatedAttributes = ['title', 'slug', 'lead', 'body', 'status', 'published_at'];

    protected $fillable = ['category_id', 'is_flash', 'is_alert', 'is_breaking', 'old_num'];

    protected $casts = [
        'is_flash' => 'boolean',
        'is_alert' => 'boolean',
        'is_breaking' => 'boolean',
        'related' => 'array'
    ];

    protected $observables = ['flash'];

    public function getId()
    {
        return $this->id;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'article_images');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'article_authors', 'article_id');
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany($this, 'related_articles', 'article_id', 'related_id');
    }

    public function gallery(): HasOne
    {
        return $this->hasOne(Gallery::class);
    }

    public function vzt(): Visits
    {
        return visits($this);
    }

    public static function getPublishedArticles(): Article
    {
        return $this
            ->join('article_translations', 'article_translations.article_id', '=', 'articles.id')
            ->where('article_translations.status', '=', 'P');
    }

    public function toSearchArray(): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'translations' => $this->translations()->get(),
            'is_flash' => $this->is_flash,
            'is_alert' => $this->is_alert,
            'is_breaking' => $this->is_breaking,
            'images' => $this->images()->get()
        ];
    }
}
