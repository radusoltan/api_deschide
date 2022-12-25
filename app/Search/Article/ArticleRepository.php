<?php

namespace App\Search\Article;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Article;

class ArticleRepository
{

    /** @var Client */
    private Client $elasticsearch;

    /**
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * @param string $query
     * @param string $locale
     * @return Collection
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function search(string $query, string $locale): Collection
    {

        $items = $this->searchOnElastic($query, $locale);

        return $this->buildCollection($items);
    }

    /**
     * @param string $query
     * @param $locale
     * @return array
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function searchOnElastic(string $query = '', $locale): array
    {
        $model = new Article;
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['translations.title' => $query]],
                            ['match' => ['translations.locale' => $locale]],
                        ]
                    ]
                ]
            ]
        ]);

        return $items->asArray();
    }

    /**
     * @param array $items
     * @return Collection
     */
    public function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return Article::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }
}
