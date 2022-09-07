<?php

namespace App\Articles;

use App\Models\Article;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchRepository implements ArticlesRepository
{
    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $query = ''): Collection
    {
        $items = $this->searchOnElasticsearch($query);
// dd($this->buildCollection($items));
        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = '')
    {
        $model = new Article;

        $items = $this->elasticsearch->search([
            'index' => 'articles',
            'type' => '_doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['translations.title' => $query]],
                            ['match' => ['translations.locale' => 'ro']]
                        ]
                    ]
                ],
            ],
        ]);

        return $items->asArray();
    }

    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        $articles = Article::findMany($ids)
            ->sortBy('id');

        // Article::findMany($ids)
        //     ->sortBy(function ($article) use ($ids) {
        //         dump($article);

        //         return array_search($article->getKey(), $ids);
        //     })

        return $articles;
    }
}
