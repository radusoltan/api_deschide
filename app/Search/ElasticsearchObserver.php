<?php

namespace App\Search;

use Elastic\Elasticsearch\Client;

class ElasticsearchObserver {
    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function saved($model)
    {
        dd($model);
    }

    public function updated($model)
    {
         dd($model);
    }

    public function deleted($model)
    {

    }
}
