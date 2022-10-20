<?php

namespace App\Search;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

trait Searchable
{
    public function elasticsearchIndex(Client $elastic)
    {
        $elastic->index([
            'index' => $this->getSearchIndex(),
            'type' => $this->getType(),
            'id' => $this->getId(),
            'body' => $this->toSearchArray()
        ]);
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function elasticsearchUpdate(Client $elastic): void
    {
        $elastic->update([
            'index' => $this->getSearchIndex(),
            'id' => $this->getId(),
            'body' => [
                'doc' => $this->toSearchArray()
            ]
        ]);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function elasticsearchDelete(Client $elasticsearchClient): void
    {
        $elasticsearchClient->delete([
            'index' => $this->getSearchIndex(),
            'type' => $this->getType(),
            'id' => $this->getId(),
        ]);
    }

    public function getSearchIndex(): string
    {
        return $this->getTable();
    }

    public function getType(): string
    {
        return '_doc';
    }

    public function getId()
    {
        return $this->id;
    }


    abstract public function toSearchArray(): array;
}
