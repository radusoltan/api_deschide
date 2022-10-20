<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Search\Author\AuthorRepository;
use App\Search\Article\ArticleRepository;
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
        $this->app->bind(AuthorRepository::class, function ($app) {
            return new AuthorRepository(
                $app->make(Client::class)
            );
        });

        $this->app->bind(ArticleRepository::class, function ($app) {
            return new ArticleRepository(
                $app->make(Client::class)
            );
        });

        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts(['http://localhost:9200'])
                ->setBasicAuthentication('elastic', 'YutC7Nzk_WHSGsQh6q5q')
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
