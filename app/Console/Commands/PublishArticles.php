<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Carbon\Carbon;

class PublishArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comanda care publica articolele trimise spre publicare ...';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $articles = Article::join('article_translations','article_translations.article_id','=','articles.id')
            ->where('article_translations.status','=','S')
            ->get();

        foreach ($articles as $article){

            $dateToPublish = Carbon::parse($article->publish_at);
            $dateNow = Carbon::now();

            if ($dateToPublish->equalTo($dateNow)) {
                $article->status = "P";
                $article->publush_at = null;
                $article->published_at = $dateNow;
                $article->save();
            }

        }

    }
}
