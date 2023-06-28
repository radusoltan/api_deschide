<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Models\Category;
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
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query = '', string $locale): Collection
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
     * @param \App\Models\Category $category
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchCategoryArticles(string $query = '', string $locale, Category $category): Collection
    {
        try {
            $items = $this->searchOnElasticByCategory($query, $locale, $category);
            return $this->buildCollection($items);
        } catch (ClientResponseException|ServerResponseException $e){}
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

    public function searchOnElasticByCategory(string $query, string $locale, Category $category) {

        $model = new Article;
        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getType(),
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['match' => ['category_id' => $category->getId()]],
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

    //////////////// Elastic search index operations

    public function getElasticIndex()
    {
        $indexes = $this->elasticsearch->get(['index'=>'articles'])->asArray();
        dump($indexes);

    }

}
