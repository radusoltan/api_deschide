<?php

namespace App\Observers;

use App\Models\Category;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

class CategoryObserver
{
    public function __construct(private Client $elasticsearchClient)
    {
    }

    /**
     * Handle the Category "created" event.
     *
     * @param Category $category
     * @return void
     */
    public function created(Category $category): void
    {

        $category->elasticsearchIndex($this->elasticsearchClient);
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param Category $category
     * @return void
     */
    public function updated(Category $category): void
    {
        try {
            $category->elasticsearchUpdate($this->elasticsearchClient);
        } catch (ClientResponseException|MissingParameterException|ServerResponseException $e) {
        }
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function deleted(Category $category): void
    {
        try {
            $category->elasticsearchDelete($this->elasticsearchClient);
        } catch (ClientResponseException|MissingParameterException|ServerResponseException $e) {
        }
    }

    /**
     * Handle the Category "restored" event.
     *
     * @param Category $category
     * @return void
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function forceDeleted(Category $category): void
    {
        try {
            $category->elasticsearchDelete($this->elasticsearchClient);
        } catch (ClientResponseException|MissingParameterException|ServerResponseException $e) {
        }
    }
}
