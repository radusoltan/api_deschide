<?php

namespace App\Services;

use Elastic\Elasticsearch\Client;
class ElasticSearchService {

//    /**
//     * @param \Elastic\Elasticsearch\Client $elasticsearchClient
//     */
//    public function __construct(private Client $elasticsearchClient)
//    {
//    }

    public function getIndexes() {
        dump('$this->elasticsearchClient()');
    }

}
