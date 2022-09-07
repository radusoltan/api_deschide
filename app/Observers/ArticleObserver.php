<?php

namespace App\Observers;

use App\Models\ArticleTranslation;

class ArticleObserver
{
    /**
     * Handle the ArticleTranslation "created" event.
     *
     * @param  \App\Models\ArticleTranslation  $articleTranslation
     * @return void
     */
    public function created(ArticleTranslation $articleTranslation)
    {
        //
    }

    public function flash(ArticleTranslation $articleTranslation){
        dump($articleTranslation);
    }

    /**
     * Handle the ArticleTranslation "updated" event.
     *
     * @param  \App\Models\ArticleTranslation  $articleTranslation
     * @return void
     */
    public function updated(ArticleTranslation $articleTranslation)
    {
        //
    }

    /**
     * Handle the ArticleTranslation "deleted" event.
     *
     * @param  \App\Models\ArticleTranslation  $articleTranslation
     * @return void
     */
    public function deleted(ArticleTranslation $articleTranslation)
    {
        //
    }

    /**
     * Handle the ArticleTranslation "restored" event.
     *
     * @param  \App\Models\ArticleTranslation  $articleTranslation
     * @return void
     */
    public function restored(ArticleTranslation $articleTranslation)
    {
        //
    }

    /**
     * Handle the ArticleTranslation "force deleted" event.
     *
     * @param  \App\Models\ArticleTranslation  $articleTranslation
     * @return void
     */
    public function forceDeleted(ArticleTranslation $articleTranslation)
    {
        //
    }
}
