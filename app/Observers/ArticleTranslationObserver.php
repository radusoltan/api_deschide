<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\ArticleTranslation;
use Elastic\Elasticsearch\Client;

class ArticleTranslationObserver
{
    private $elastic;
    public function __construct(Client $elastic){
        $this->elastic = $elastic;
    }
    /**
     * Handle the ArticleTranslation "created" event.
     */
    public function created(ArticleTranslation $articleTranslation): void
    {
        $article = Article::find($articleTranslation->article_id);

        if($articleTranslation->status === 'P') {
            $article->elasticsearchIndex($this->elastic);
        }
    }

    /**
     * Handle the ArticleTranslation "updated" event.
     */
    public function updated(ArticleTranslation $articleTranslation): void
    {
        $article = Article::find($articleTranslation->article_id);

        if($articleTranslation->status === 'P') {
            $article->elasticsearchUpdate($this->elastic);
        } else {
            $article->elasticsearchDelete($this->elastic);
        }
    }

    /**
     * Handle the ArticleTranslation "deleted" event.
     */
    public function deleted(ArticleTranslation $articleTranslation): void
    {
        $article = Article::find($articleTranslation->article_id);
        $article->elasticsearchDelete($this->elastic);
    }

    /**
     * Handle the ArticleTranslation "restored" event.
     */
    public function restored(ArticleTranslation $articleTranslation): void
    {
        $article = Article::find($articleTranslation->article_id);

        if($articleTranslation->status === 'P') {
            $article->elasticsearchIndex($this->elastic);
        }
    }

    /**
     * Handle the ArticleTranslation "force deleted" event.
     */
    public function forceDeleted(ArticleTranslation $articleTranslation): void
    {
        $article = Article::find($articleTranslation->article_id);
        $article->elasticsearchDelete($this->elastic);
    }
}
