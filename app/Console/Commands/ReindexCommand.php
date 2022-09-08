<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
// use Elastic\Elasticsearch\ClientBuilder;

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

    // /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct()
    {
        parent::__construct();

        // $this->elasticsearch = ClientBuilder::create()
        //         ->setHosts(config('services.search.hosts'))
        //         ->setBasicAuthentication('elastic', config('services.search.creds'))
        //         ->build();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->info('Indexing all articles. This might take a while...');
        // // dump(Article::cursor());
        // foreach (Article::cursor() as $article)
        // {
        //     // dd($article->toSearchArray());
        //     $this->elasticsearch->index([
        //         'index' => 'articles',
        //         'type' =>  '_doc',
        //         'id' => $article->getKey(),
        //         'body' => $article->toSearchArray(),
        //     ]);

        //     // PHPUnit-style feedback
        //     $this->output->write('.');
        // }

        // $this->info("\nDone!");
    }
}
