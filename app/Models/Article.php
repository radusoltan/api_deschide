<?php

namespace App\Models;

use App\Models\Scopes\ArticleScope;
use App\Models\Traits\Searchable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Awssat\Visits\Visits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;
    use SoftDeletes;


    public array $translatedAttributes = ['title', 'slug', 'lead', 'body', 'status', 'published_at','publish_at'];

    protected $fillable = ['category_id', 'is_flash', 'is_alert', 'is_breaking', 'old_number'];

    protected $casts = [
        'is_flash' => 'boolean',
        'is_alert' => 'boolean',
        'is_breaking' => 'boolean',
        'related' => 'array'
    ];

    protected $observables = ['flash'];

    public function getSearchIndex(): string
    {
        return $this->getTable();

    }

    public function foreignList(){
        return $this->belongsTo(ArticleList::class);
    }

    public function setIsFlash()
    {
        $this->is_alert = false;
        $this->is_breaking = false;
        $this->is_flash = true;

        $this->save();

        return $this;

    }

    public function setIsAlert()
    {
        $this->is_alert = true;
        $this->is_breaking = false;
        $this->is_flash = false;

        $this->save();

        return $this;

    }

    public function setIsBreaking()
    {
        $this->is_alert = false;
        $this->is_breaking = true;
        $this->is_flash = false;

        $this->save();

        return $this;

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'article_images')->withPivot('is_main');
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

    public function vzt()//: Visits
    {
        return visits($this);
    }
//
    public function visits()
    {
        return visits($this)->relation();
    }

    public static function getLastPublishedArticles()
    {
        return Article::join('article_translations', 'article_translations.article_id', '=', 'articles.id')
            ->where('article_translations.status', '=', 'P')

            ->with(['images','images.thumbnails'])
            ->orderBy('article_translations.published_at','DESC')
            ->limit(15)
            ->get()
            ;
    }

    public function getPublishedArticles(){
        return Article::with('translations')
            ->where('translations.status','=','P')
            ->get();
    }

    public function toSearchArray(): array
    {

        return $this->load('images','authors', 'images.thumbnails','visits')->toArray();
    }

}
