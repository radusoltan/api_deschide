<?php

namespace App\Observers;

use App\Models\Author;
use Elastic\Elasticsearch\Client;

class AuthorObserver
{
    public function __construct(private Client $elasticsearchClient)
    {
    }
    /**
     * Handle the Author "created" event.
     *
     * @param Author $author
     * @return void
     */
    public function created(Author $author): void
    {
        // $author->elasticsearchIndex($this->elasticsearchClient);
    }

    /**
     * Handle the Author "updated" event.
     *
     * @param Author $author
     * @return void
     */
    public function updated(Author $author): void
    {
        // $author->elasticsearchUpdate($this->elasticsearchClient);
    }

    /**
     * Handle the Author "deleted" event.
     *
     * @param Author $author
     * @return void
     */
    public function deleted(Author $author): void
    {
        // $author->elasticsearchDelete($this->elasticsearchClient);
    }

    /**
     * Handle the Author "restored" event.
     *
     * @param Author $author
     * @return void
     */
    public function restored(Author $author): void
    {
        //
    }

    /**
     * Handle the Author "force deleted" event.
     *
     * @param Author $author
     * @return void
     */
    public function forceDeleted(Author $author): void
    {
        // $author->elasticsearchDelete($this->elasticsearchClient);
    }
}
