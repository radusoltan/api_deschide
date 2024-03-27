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
        for ($i=0;$i<100;$i++){
            /**
             * (integer) - The number of paragraphs to generate.
             * short, medium, long, verylong - The average length of a paragraph.
             * decorate - Add bold, italic and marked text.
             * link - Add links.
             * ul - Add unordered lists.
             * ol - Add numbered lists.
             * dl - Add description lists.
             * bq - Add blockquotes.
             * code - Add code samples.
             * headers - Add headers.
             * allcaps - Use ALL CAPS.
             * prude - Prude version.
             * plaintext - Return plain text, no HTML.
             */
            $body = Http::get('https://loripsum.net/api/10/headers/link/ul/ol/bq/decorate');
            $lead = Http::get('https://loripsum.net/api/1/link/decorate');
            app()->setLocale('ro');
            $title = '// RO //'.fake()->sentence();

            $article = Article::create([
                'category_id' => fake()->randomKey(Category::pluck('id','id')->all()),
                'title' => $title,
                'slug' => Str::slug($title),
                'lead' => $lead->body(),
                'body' => $body->body(),
                'status' => "P"
            ]);

            app()->setLocale('en');
            $title = '// EN //'.fake()->sentence();
            $article->update([
                'title' => $title,
                'slug' => Str::slug($title),
                'lead' => $lead->body(),
                'body' => $body->body(),
                'status' => "P"
            ]);
            app()->setLocale('ru');
            $title = '// RU //'.fake()->sentence();
            $article->update([
                'title' => $title,
                'slug' => Str::slug($title),
                'lead' => $lead->body(),
                'body' => $body->body(),
                'status' => "P"
            ]);

        }

    }
}
