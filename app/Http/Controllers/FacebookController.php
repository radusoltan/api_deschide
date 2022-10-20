<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\FacebookPost;
use App\Services\ImportService;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use MongoClient;
use SimpleXMLElement;
use GuzzleHttp\Client;

class FacebookController extends Controller
{
    const TOKEN = 'EAAFOZAm5DHSYBAENvTIVRCIRBV4CkpZC0t5Wcm7N6RPZANkZCjBsPtWIL2UKQCRciMo0wcT9zEIdSOEP8cXMorD1RZAXN7744njQ4QQs2ZCNKa7sQz2XgXYZA7Vl5UMf2qYqKMqRo3XHDOLZCCJT5cUhgTvmuZA2ZBcbjXIs6DTi4BeEpY4OPZCcrBC7yxpX9K7eJtkMPDhQ608GgZDZ';

    private $facebook;
    private $helper;
    private $service;
    private $client;

    public function __construct(ImportService $service, Client $client)
    {

        $this->facebook = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v15.0',
        ]);

        $this->client = new Client();

        $this->helper = $this->facebook->getRedirectLoginHelper();
        $this->service = $service;
    }

    public function FacebookLogin()
    {
        $permissions = ['email', 'publish_action,manage_pages', 'publish_pages']; // Optional permissions
        $loginUrl = $this->helper->getLoginUrl(route('facebook-callback'), $permissions);

        return redirect($loginUrl);
    }

    public function handleProviderFacebookCallback()
    {
        //        $helper = $this->facebook->getRedirectLoginHelper();
        try {

            $accessToken = $this->helper->getAccessToken();
            dd($accessToken);

            if (!isset($accessToken)) {
                if ($this->helper->getError()) {
                    //                    header('HTTP/1.0 401 Unauthorized');
                    echo "Error: " . $this->helper->getError() . "\n";
                    echo "Error Code: " . $this->helper->getErrorCode() . "\n";
                    echo "Error Reason: " . $this->helper->getErrorReason() . "\n";
                    echo "Error Description: " . $this->helper->getErrorDescription() . "\n";
                } else {
                    //                    header('HTTP/1.0 400 Bad Request');
                    echo 'Bad request';
                }
                exit;
            }
        } catch (FacebookResponseException | FacebookSDKException $e) {
            dump($e->getMessage());
        }
    }

    public function postNews($url, $title)
    {

        //        $user = Auth::user();
        //
        //        $token = $user->getFacebookToken();

        $data = [
            'link' => trim((string) $url),
            'message' => $title
        ];

        // dump($data);

        try {
            //            $response = $this->facebook->get('/me?fields=id,email,accounts', $token)->getGraphUser();
            $response = $this->facebook->post('/507699295948485/feed', $data, self::TOKEN);
            return $response->getDecodedBody()['id'];
        } catch (FacebookResponseException | FacebookSDKException $exception) {
            dump($exception);
        }
    }

    public function RssArticles()
    {
        //        phpinfo();
        //        $this->service->getCategories();

        $feed = simplexml_load_file('https://deschide.md/ro/feed');
        $namespaces = $feed->getDocNamespaces(true);



        //
        //
        $newscount = 3;

        while ($newscount >= 0) {

            $article = $feed->channel->item[$newscount];

            $title = trim($article->title);
            $link = trim($article->link);

            $fb_post = FacebookPost::where([
                ['old_num', '=', $article->num]
            ])->first();



            if (!$fb_post) {
                $fb_id = $this->postNews($link, $title);
                $fb_post = FacebookPost::create([
                    'old_num' => $article->num,
                    'fb_id' => $fb_id
                ]);

                dump('Articolul // ' . $title . ' // a fost publicat pe Facebook');
            } else {
                dump('Articolul ' . $title . ' a fost deja publicat pe Facebook');
            }


            $newscount--;
        }
    }

    private function importOldArticle(SimpleXMLElement $item)
    {



        $res = \Http::get('https://deschide.md/api/articles/' . $item->num . '.json');
        $old = json_decode($res->body());
        //
        if (property_exists($item, 'section')) {
            $category = Category::where('old_number', $old->section->number)->first();
            $article = Article::where('old_number', $old->number)->first();
            if (!$article) {
                app()->setLocale($old->language);
                $article = Article::create([
                    'title' => $old->title,
                    'slug' => \Str::slug($old->title),
                    'lead' => $old->fields->lead,
                    'body' => $old->fields->Continut,
                    'is_breaking' => $old->fields->BREAKING_NEWS,
                    'is_alert' => $old->fields->NEWS_ALERT,
                    'is_flash' => $old->fields->FLASH,
                    'old_number' => $old->number,
                    'category_id' => $category->getId(),
                    'status' => $old->status === 'Y' ? 'P' : 'S',
                    //                    'share_id' => $this->postNews($old->url, $old->title)
                ]);
            }
            dump($article);
        }
    }
}
