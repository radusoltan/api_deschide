<?php

namespace App\Providers;

use App\Observers\ArticleObserver;
use Illuminate\Support\ServiceProvider;
use App\Search\Author\AuthorRepository;
use App\Repositories\ArticleRepository;
use App\Search\Category\CategoryRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ArticleRepository::class, function ($app){

            return new ArticleRepository(
                $app->make(Client::class)
            );

        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([env('ELASTICSEARCH_HOST')])
                ->setBasicAuthentication(env('ELASTICSEARCH_USER'), env('ELASTICSEARCH_PASS'))
                ->build();
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
