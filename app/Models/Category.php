<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Search\Searchable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;

    public $translatedAttributes = ['title','slug'];
    protected $fillable = ['in_menu','old_number'];
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
}
