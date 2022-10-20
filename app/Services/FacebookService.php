<?php

namespace App\Services;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookService
{
    const TOKEN = 'EAAFOZAm5DHSYBAK7FwdRRA1nO3Gz7lUFZAWKiU5YDXPQ7ARfKnZAMykgydSQ51vYzEhFtiYf2paLserbi5I9oxhZB0M1HJQbikOmmJQx2jJmZA4w5auraBbIMFeBpT3iZB8qnkBxFscR5MVugYZBUEzLoDuOekdsvjAUCkuLwKisbiFZB688rn3NL3kSzhdrZAgIEggHVpwUiEr2adjTtvz1Vl1pbX7DgvSMZD';

    private $facebook;

    /**
     * @throws FacebookSDKException
     */
    public function __construct(Facebook $facebook)
    {
        $this->facebook = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v2.10',
            'default_access_token' => self::TOKEN
        ]);
    }

    public function postNeews()
    {

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $this->facebook->get('/me', );
            dump($response);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }



    }



}
