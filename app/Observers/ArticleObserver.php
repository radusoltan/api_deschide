<?php

namespace App\Observers;

use App\Models\Article;
use Elastic\Elasticsearch\Client;

class ArticleObserver
{
    /**
     * @param Client $elasticsearchClient
     */
    public function __construct(private Client $elasticsearchClient)
    {
    }

    /**
     * Handle the Article "created" event.
     *
     * @param Article $article
     * @return void
     */
    public function created(Article $article)
    {
//         $article->elasticsearchIndex($this->elasticsearchClient);
    }

    public function flash(Article $article)
    {
        // dump($article);
    }

    /**
     * Handle the Article "updated" event.
     *
     * @param Article $article
     * @return void
     */
    public function updated(Article $article): void
    {
//        dump($article);
         $article->elasticsearchUpdate($this->elasticsearchClient);
    }

    /**
     * Handle the Article "deleted" event.
     *
     * @param Article $article
     * @return void
     */
    public function deleted(Article $article)
    {
         $article->elasticsearchDelete($this->elasticsearchClient);
    }

    /**
     * Handle the Article "restored" event.
     *
     * @param Article $article
     * @return void
     */
    public function restored(Article $article)
    {

        // $article->elasticsearchIndex($this->elasticsearchClient);
    }

    /**
     * Handle the Article "force deleted" event.
     *
     * @param Article $article
     * @return void
     */
    public function forceDeleted(Article $article)
    {
        // $article->elasticsearchDelete($this->elasticsearchClient);
    }
}
