<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\Client;
use Illuminate\Http\Request;
use App\Services\ElasticSearchService;
use App\Repositories\ArticleRepository;
class ElasticSearchController extends Controller
{

//    public function __construct(private Client $elascticsearch) {
//
//    }

    public function getIndexes() {
        dump('here');
////        $service = new ElasticSearchService();
////        $service->getIndexes();
//        $repo = new ArticleRepository($this->elascticsearch);
//        $repo->getElasticIndex();
    }
}
