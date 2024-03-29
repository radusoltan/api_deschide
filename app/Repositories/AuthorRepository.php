<?php

namespace App\Repositories;

use App\Models\Author;
use Elastic\Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class AuthorRepository
{

    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $query = '', string $locale): Collection
    {

        $items = $this->searchOnElasticsearch($query, $locale);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = '', string $locale): array
    {
        $model = new Author;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getType(),
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'term' => [
                                    'translations.locale' => 'ro'
                                ]
                            ],
                            [
                                'match' => [
                                    'translations.full_name' => request('q')
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        return $items->asArray();
    }

    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return Author::findMany($ids)
            ->sortBy(function ($author) use ($ids) {
                return array_search($author->getId(), $ids);
            });
    }
}
