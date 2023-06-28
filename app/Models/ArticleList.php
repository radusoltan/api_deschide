<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleList extends Model
{
    use HasFactory;
    protected $fillable = ['name','max_item_count'];

    public function articles(){
        return $this->belongsToMany(Article::class,'featured_articles_list','article_list_id','article_id');
    }

    public function setCount() {
        $this->count = $this->articles()->count();
        $this->save();

        return $this;
    }
}
