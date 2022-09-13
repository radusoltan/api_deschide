<?php

namespace App\Search;

trait Searchable {

    public static function bootSercheable(){
        static::observe(ElasticSearchObserver::class);
    }

    public function elasticsearchIndex(Client $elastic)
    {
        $elastic->index([
            'index' => $this->getSearchIndex(),
            'type' => '_doc',
            'id' => $this->getId(),
            'body' => $this->toSearchArray()
        ]);
    }
    abstract public function toSearchArray(): array;
}
