<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Console\Command;
use App\Models\Article;
use Elastic\Elasticsearch\Client;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all articles to Elasticsearch';


    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->info('Indexing all articles. This might take a while...');

        foreach (Article::cursor() as $article){
             if($article->status === 'P') {

                 $this->elasticsearch->index([
                     'index' => $article->getSearchIndex(),
                     'type' => $article->getType(),
                     'id' => $article->getId(),
                     'body' => $article->toSearchArray()
                 ]);
             }

            $this->output->write('.');
        }


        $this->info('Indexing all authors. This might take a while...');


        foreach (Author::cursor() as $author){
            $this->elasticsearch->index([
                'index' => $author->getSearchIndex(),
                'type' => $author->getType(),
                'id' => $author->getId(),
                'body' => $author->toSearchArray()
            ]);
            $this->output->write('.');
        }

        $this->output->write('Done !');



    }
}
