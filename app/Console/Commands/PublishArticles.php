<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\ArticleTranslation;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
        $translations = ArticleTranslation::where('status',"S")
            ->whereNotNull('publish_at')
            ->get();

        foreach ($translations as $translation){
            $dateToPublish = Carbon::parse($translation->publish_at);
            $dateNow = Carbon::now();
            if (
                $dateToPublish->format('H') == Carbon::now()->format('H') &&
                $dateToPublish->format('i') == Carbon::now()->format('i')
            ) {
                app()->setLocale($translation->locale);
                $translation->update([
                    'status' => 'P',
                    'publish_at' => null,
                    'published_at' => $dateNow
                ]);
            }
        }

//        foreach ($articles as $article){
//
//            $dateToPublish = Carbon::parse($article->publish_at);
//            $dateNow = Carbon::now();
//
//            $publishAt = Carbon::parse($article->publish_at);
//
//            // Verifică dacă ora și minutul din publish_at sunt aceleași cu cele curente
//            if ($dateToPublish->format('H') == Carbon::now()->format('H') && $dateToPublish->format('i') == Carbon::now()->format('i')) {
//                // Aceeași oră și minut
//
//                app()->setLocale($article->locale);
//                $article->update([
//                   'status' => 'P',
//                   'publish_at' => null,
//                   'published_at' => $publishAt
//                ]);
//            }
//
//        }

    }
}
