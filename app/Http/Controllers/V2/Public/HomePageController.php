<?php

namespace App\Http\Controllers\V2\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HomePageController extends Controller
{

    public function getInitialProps(){
        return [
            'categories' => Category::where('in_menu', true)->get()
        ];
    }

}
