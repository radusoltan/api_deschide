<?php

namespace App\Http\Controllers\V2\Public;

use App\Http\Controllers\Controller;

class HomePageController extends Controller
{

    public function getInitialProps(){
        return [
            'some props' => []
        ];
    }

}
