<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RssReaderController extends Controller
{
    private $feed;
    public function __contruct(){
        $this->feed = simplexml_load_file('https://deschide.md/ro/feed');
    }

    public function readRss(){

        $feed = simplexml_load_file('https://deschide.md/ro/feed/');

        foreach ($feed->channel->item as $item){
            $description = $item->description;
            dump($item);
        }
    }
}
