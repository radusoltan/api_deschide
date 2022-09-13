<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Search\ElasticsearchRepository;
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
        $this->app->bind(ElasticsearchRepository::class, function($app){
            return new ElasticsearchRepository(
                $app->make(Client::class)
            );
        });

        $this->app->bind(Client::class,function(){
            return ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication('elastic', 'YutC7Nzk_WHSGsQh6q5q')
            ->build();
        });

    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class,function(){
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
