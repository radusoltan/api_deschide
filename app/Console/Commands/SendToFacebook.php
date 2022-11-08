<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\Category;
use App\Models\FacebookPost;
use App\Services\ImportService;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use GuzzleHttp\Client;

class SendToFacebook extends Command
{

    const TOKEN = 'EAAFOZAm5DHSYBAENvTIVRCIRBV4CkpZC0t5Wcm7N6RPZANkZCjBsPtWIL2UKQCRciMo0wcT9zEIdSOEP8cXMorD1RZAXN7744njQ4QQs2ZCNKa7sQz2XgXYZA7Vl5UMf2qYqKMqRo3XHDOLZCCJT5cUhgTvmuZA2ZBcbjXIs6DTi4BeEpY4OPZCcrBC7yxpX9K7eJtkMPDhQ608GgZDZ';

    private $facebook;
    private $helper;
    private $service;
    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->facebook = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v15.0',
        ]);
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:share';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Share articles to Facebook page';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Posting on Facebook...');
        $feed = simplexml_load_file('https://deschide.md/ro/feed');

        dd($feed);

        $newscount = 3;

        while ($newscount >= 0) {



            $article = $feed->channel->item[$newscount];

            $title = trim($article->title);
            $link = trim($article->link);

            //     $fb_post = FacebookPost::query()
            //         ->where([
            //             'old_num', '=', $article->num
            //         ])
            //         ->whereFullText('facebook_posts' . 'title', $title)
            //         ->first();

            $fb_post = FacebookPost::query()
                // ->where([
                //     ['old_num', '=', $article->num]
                // ])
                ->whereFullText('title', $title)
                ->first();

            //     // dump($fb_post);

            //     // $fb_post = FacebookPost::where([
            //     //     [
            //     //         'old_num', '=', $article->num
            //     //     ]
            //     // ])->first();

            if (!$fb_post) {
                $fb_id = $this->postNews($link, $title);
                $fb_post = FacebookPost::create([
                    'old_num' => $article->num,
                    'fb_id' => $fb_id,
                    'title' => $title
                ]);

                dump('Articolul // ' . $title . ' // a fost publicat pe Facebook');
            } else {
                dump('Articolul ' . $title . ' a fost deja publicat pe Facebook');
            }



            $newscount--;
        }
        // die;
    }

    private function postNews($link, $title)
    {

        $data = [
            'link' => $link,
            'message' => $title
        ];

        try {
            $response = $this->facebook->post('/507699295948485/feed', $data, self::TOKEN);
            return $response->getDecodedBody()['id'];
        } catch (FacebookResponseException | FacebookSDKException $exception) {
            dump($exception);
        }
    }
}
