<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Facebook\Facebook;
use Illuminate\Support\ServiceProvider;

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
                ->setHosts(config('services.search.hosts'))
                ->setBasicAuthentication(config('services.search.user'), config('services.search.pass'))
                ->build();
        });
    }

    private function bindFacebook(){
        $this->app->bind(Facebook::class, function (){
            return new Facebook([
                'app_id' => config('services.facebook.client_id'),
                'app_secret' => config('services.facebook.client_secret'),
                'default_graph_version' => config('services.facebook.default_graph_version'),
            ]);
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
