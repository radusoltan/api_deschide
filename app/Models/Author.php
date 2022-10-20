<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Search\Searchable;

class Author extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Searchable;

    public $translatedAttributes = [
        'first_name',
        'last_name',
        'full_name',
        'slug'
    ];

    protected $fillable = [
        'email',
        'facebook'
    ];

    public function toSearchArray(): array
    {
        return [
            'id' => $this->getId(),
            'translations' => $this->translations()->get()
        ];
    }

}
