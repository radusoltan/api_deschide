<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Search\Searchable;

class ArticleTranslation extends Model
{
    use HasFactory;
    // use Searchable;
    public $timestamps = false;
    protected $fillable = ['title','slug','lead','body','keywords','status', 'published_at','publish_at'];

    protected $casts = [
        'publish_at' => 'datetime',
        'keywords' => 'json'
    ];

    public function scopePublished($query)
    {
        return $query->where('published_at','<=',now());
    }
}
