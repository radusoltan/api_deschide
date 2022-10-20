<?php

namespace App\Search\Category;

use App\Models\Category;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{

    /** @var \Elastic\Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $query, string $locale): Collection
    {

        $items = $this->searchOnElastic($query, $locale);

        return $this->buildCollection($items);
    }

    public function searchOnElastic(string $query = '', $locale)
    {
        $model = new Category;
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getType(),
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

    public function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        $articles = Category::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
        return $articles;
    }
}
