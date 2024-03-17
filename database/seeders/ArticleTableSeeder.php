<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0;$i<1000;$i++){
            app()->setLocale('ro');
            $title = '// RO //'.fake()->sentence();

            $article = Article::create([
                'category_id' => fake()->randomKey(Category::pluck('id','id')->all()),
                'title' => $title,
                'slug' => Str::slug($title),
                'lead' => fake()->paragraph(),
                'body' => fake()->paragraph(),
                'status' => "P"
            ]);

            app()->setLocale('en');
            $title = '// EN //'.fake()->sentence();
            $article->update([
                'title' => $title,
                'slug' => Str::slug($title),
                'lead' => fake()->paragraph(),
                'body' => fake()->paragraph(),
                'status' => "P"
            ]);
            app()->setLocale('ru');
            $title = '// RU //'.fake()->sentence();
            $article->update([
                'title' => $title,
                'slug' => Str::slug($title),
                'lead' => fake()->paragraph(),
                'body' => fake()->paragraph(),
                'status' => "P"
            ]);

        }

//        foreach (config('translatable.locales') as $locale){
//            app()->setLocale($locale);
//
//            $response = Http::get('https://deschide.md/api/articles?items_per_page=5&language='.app()->getLocale());
//            $items = $response->json();
//
//            foreach ($items['items'] as $item){
//                $category = Category::where('old_number', $item["section"]["number"])->first();
//
//                $article = Article::where('old_number',$item['number'])->first();
//
//                $publish_at_date = Carbon::parse($item['published']);
//
//                if (!$article){
//                    if (isset($item['fields']['Continut'])){
//                        $article = Article::create([
//                            'category_id' => $category->getId(),
//                            'title' => $item['title'],
//                            'slug' => Str::slug($item['title']),
//                            'lead' => $item['fields']['lead'] ?? '',
//                            'body' => $item['fields']['Continut'],
//                            'status' => $item['status']==='Y' ? 'P' : 'another',
//                            'old_number' => $item['number'],
//                            'published_at' => $publish_at_date
//                        ]);
//                    }
//                }
//            }
//        }

    }
}
