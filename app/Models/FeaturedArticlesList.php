<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedArticlesList extends Model
{
    use HasFactory;
    public $table = 'featured_articles_list';
    public $timestamps = false;
    protected $fillable = ['order', 'article_id', 'article_list_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function articleList()
    {
        return $this->belongsTo(ArticleList::class);
    }

}
