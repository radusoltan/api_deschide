<?php

namespace App\Models\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
class ArticleScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->join('article_translations','article_translations.article_id','=','articles.id');
    }
}
