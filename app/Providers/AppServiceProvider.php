<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Articles\EloquentSearchRepository;
use App\Articles\SearchRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use App\Articles;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // dump('app service provider');
        // $this->app->bind(SearchRepository::class, EloquentSearchRepository::class);
        $this->app->bind(Articles\ArticlesRepository::class, function ($app) {
        //     // This is useful in case we want to turn-off our
        //     // search cluster or when deploying the search
        //     // to a live, running application at first.
            if (! config('services.search.enabled')) {
                return new Articles\EloquentSearchRepository();
            }

            return new Articles\ElasticsearchRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->setBasicAuthentication('elastic', $app['config']->get('services.search.creds'))
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
