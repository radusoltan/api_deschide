<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Facebook\Facebook;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

//        $this->app->bind(ArticleRepository::class, function ($app){
//
//            return new ArticleRepository(
//                $app->make(Client::class)
//            );
//
//        });
//

        $this->bindSearchClient();
    }

        $this->app->bind(Client::class, function (){
            return ClientBuilder::create()

                ->setHosts(['https://localhost:9200'])
                ->setBasicAuthentication(config('services.search.user'),config('services.search.pass'))
                ->setCABundle(base_path().'/http_ca.crt')
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
//        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page' ){
//            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
//            return new LengthAwarePaginator(
//                $this->forPage($page, $perPage),
//                $total ?: $this->count(),
//                $perPage,
//                $page,
//                [
//                    'path' => LengthAwarePaginator::resolveCurrentPath(),
//                    'pageName' => $pageName
//                ]
//            );
//        });
    }
}
