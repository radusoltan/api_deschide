<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\Image;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository
{
    /**
     * @var Client
     *
     */
    private Client $elasticsearch;

    /**
     * @param Client $elasticsearch
     */
    public function __construct(Client $elasticsearch){
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * @param string $query
     * @param string $locale
     * @return Collection|void
     */
    public function search(string $query = '', string $locale)//: Collection
    {
        try {
            $items = $this->searchOnElasticsearch($query, $locale);
            return $this->buildCollection($items);
        } catch (ClientResponseException|ServerResponseException $e) {
        }

    }

    /**
     * @param string $query
     * @param string $locale
     * @return array
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    private function searchOnElasticsearch(string $query, string $locale): array
    {
        $model = new Article;
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getType(),
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['translations.title' => $query]],
                            ['match' => ['translations.locale' => $locale]]
                        ]
                    ],

                ]
            ],
            'size' => 100,
            'from' => 0
        ]);

        return $items->asArray();
    }

    private function buildCollection(array $items): Collection
    {
        $ids = \Arr::pluck($items['hits']['hits'], '_id');
        return Article::findMany($ids)
            ->sortBy(function ($article) use ($ids){
                return array_search($article->getId(), $ids);
            });
    }

    public function getLastPublishedArticles()
    {
        $locale = request('locale');
        app()->setLocale($locale);

        $publishedIds = ArticleTranslation::where('status','=','P')->distinct()
            ->orderBy('published_at',"DESC")
            ->limit(15)->pluck('article_id')->toArray();

        return Article::findMany($publishedIds)->load(['images', 'images.thumbnails'])
            ->sortBy(function ($article) use ($publishedIds){
                return array_search($article->getId(), $publishedIds);
            });

    }

}
