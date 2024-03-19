<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use App\Repositories\ArticleRepository;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Arr;

class HomepageController extends Controller
{
    private $elastic;

    public function __construct(Client $elastic) {
        $this->elastic = $elastic;
    }

    public function getInitialProps()
    {

        $params = [
            'index' => 'articles',
            'type' => '_doc',
            'size' => 10,
            'body'   => [
                'query' => [
                    'match' => [
                        "translations.locale" => 'ru'
                    ]
                ],
                'sort' => [
                    'created_at' => [
                        'order' => 'desc',
                        'unmapped_type' => 'date', // Optional, if the field type is not explicitly mapped
                    ],
                ],
            ]
        ];

        $response = $this->elastic->search($params)->asArray();
        $obj = $this->elastic->search($params)->asObject();

        $ids = Arr::pluck($response['hits']['hits'],['_id']);

        $articles = \App\Models\Article::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
        app()->setLocale(request('locale'));
//        $categories =
//        dump($categories);
        return [
            'categories' => Category::all(),
            'latestPublishedArticles' => Article::getLastPublishedArticles(),
            'arts' => $articles
//            'defaultImage' => Image::find(1)->with('thumbnails')
        ];
    }


}
