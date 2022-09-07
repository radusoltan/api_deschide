<?php
namespace App\Articles;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Articles\SearchRepository;

class EloquentSearchRepository implements SearchRepository
{
    public function search(string $term): LengthAwarePaginator
    {
        // dump(__CLASS__);
        // dump($term);
        return Article::query()
            ->join('article_translations','article_translations.article_id','=','articles.id')
            ->where(fn ($query) => (
                $query->where('article_translations.body', 'LIKE', "%{$term}%")
                    ->orWhere('article_translations.title', 'LIKE', "%{$term}%")
                    ->orWhere('article_translations.lead','LIKE',"%{$term}%")
                    ->orWhere('article_translations.keywords','LIKE',"%{$term}%")
            ))
            ->paginate(10);
    }
}
