<?php

namespace App\Search;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use App\Models\Article;
class ElasticSearch {

    const ARTICLE_INDEX = 'articles';
    const CATEGORY_INDEX = 'categories';
    const IMAGE_INDEX = 'images';

    private $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->setBasicAuthentication('elastic', 'YutC7Nzk_WHSGsQh6q5q')
            ->build();
    }

    public function getIndexes()
    {
        $params = ['index' => 'articles'];
        $response = $this->client->info();
        return $response->getBody();

    }

    public function getArticle(Article $article){
        $params = [
            'index' => self::ARTICLE_INDEX,
            'id'    => $article->id
        ];
        return $this->client->get($params);
    }
}
