<?php

namespace App\Services;

use App\Models\Category;
use Http;
use Illuminate\Support\Str;

class ImportService
{
    const RO_FEED = 'https://deschide.md/ro/feed';

    public function __construct(){

    }

    public function getCategories(){

//        dump(config('translatable.locales'));
        foreach (config('translatable.locales') as $locale){
            $res = Http::get('https://deschide.md/api/sections.json?items_per_page=100&language='.$locale);

            $categories = json_decode($res->body());

            if ($locale === 'ro'){

                foreach ($categories->items as $item){
                    $this->importCategory($item);
                }
            } else {
                $this->translateCategories($categories->items);
            }
        }

    }

    public function getArticlesFromRss(){

        $feed = simplexml_load_file('https://deschide.md/ro/feed');

        foreach ($feed->channel->item as $item){
            $description = $item->description;
            dump($item);
        }

    }

    private function importCategory(mixed $category)
    {
        app()->setLocale($category->language);

        $cat = Category::where('old_number',$category->number)->first();

        if (!$cat){
            $cat = Category::create([
                'in_menu' => true,
                'title' => ucfirst($category->title),
                'slug' => Str::slug(ucfirst($category->title)),
                'old_number' => $category->number
            ]);
        }
    }

    private function translateCategories(mixed $categories)
    {
        foreach($categories as $category) {
            app()->setLocale($category->language);
            $cat = Category::where('old_number',$category->number)->first();
            $cat->update([
                'title' => ucfirst($category->title),
                'slug' => Str::slug(ucfirst($category->title)),
            ]);
        }
    }

}
