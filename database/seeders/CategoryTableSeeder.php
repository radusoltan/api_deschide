<?php

namespace Database\Seeders;

use Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'ro' => [
                    'title' => 'Politic',
                    'slug' => Str::slug('Politic'),
                    'old_number' => 1
                ],
                'ru' => [
                    'title' => 'Политика',
                    'slug' => Str::slug('Политика')
                ],
                'en' => [
                    'title' => 'Political',
                    'slug' => Str::slug('Political')
                ]
            ],
            [
                'ro' => [
                    'title' => 'Social',
                    'slug' => Str::slug('Social'),
                    'old_number' => 2
                ],
                'ru' => [
                    'title' => 'Общество',
                    'slug' => Str::slug('Общество')
                ],
                'en' => [
                    'title' => 'Social',
                    'slug' => Str::slug('Social')
                ]
            ],
            [
                'ro' => [
                    'title' => 'Economic',
                    'slug' => Str::slug('Economic'),
                    'old_number' => 3

                ],
                'ru' => [
                    'title' => 'Экономика',
                    'slug' => Str::slug('Экономика')
                ],
                'en' => [
                    'title' => 'Financial',
                    'slug' => Str::slug('Financial')
                ]
            ],
            [
                'ro' => [
                    'title' => 'Cultura',
                    'slug' => Str::slug('Cultura'),
                    'old_number' => 7
                ],
                'ru' => [
                    'title' => 'Культура',
                    'slug' => Str::slug('Культура')
                ],
                'en' => [
                    'title' => 'Cultural',
                    'slug' => Str::slug('Cultural')
                ]
            ],
            [
                'ro' => [
                    'title' => 'Sport',
                    'slug' => Str::slug('Sport'),
                    'old_number' => 8

                ],
                'ru' => [
                    'title' => 'Спорт',
                    'slug' => Str::slug('Спорт')
                ],
                'en' => [
                    'title' => 'Sport',
                    'slug' => Str::slug('Sport')
                ]
            ],
            [
                'ro' => [
                    'title' => 'Externe',
                    'slug' => Str::slug('Externe'),
                    'old_number' => 4
                ],
                'ru' => [
                    'title' => 'B мире',
                    'slug' => Str::slug('B мире')
                ],
                'en' => [
                    'title' => 'International',
                    'slug' => Str::slug('International')
                ]
            ],
            [
                'ro' => [
                    'title' => 'Editorial',
                    'slug' => Str::slug('Editorial'),
                    'old_number' => 5
                ],
                'ru' => [
                    'title' => 'мнения',
                    'slug' => Str::slug('мнения')
                ],
                'en' => [
                    'title' => 'Editorials',
                    'slug' => Str::slug('Editorials'),
                ]
            ],
            [
                'ro' => [
                    'title' => 'Investigații',
                    'slug' => Str::slug('Investigații'),
                    'old_number' => 6
                ],
                'ru' => [
                    'title' => 'Pасследования',
                    'slug' => Str::slug('Pасследования')
                ],
                'en' => [
                    'title' => 'Investigations',
                    'slug' => Str::slug('Investigations')
                ]
            ],
            [
                'ro' => [
                    'title' => 'Anti-Fake',
                    'slug' => Str::slug('Anti-Fake'),
                    'old_number' => 26
                ],
                'ru' => [
                    'title' => 'Anti-Fake',
                    'slug' => Str::slug('Anti-Fake')
                ],
                'en' => [
                    'title' => 'Anti-Fake',
                    'slug' => Str::slug('Anti-Fake')
                ]
            ],

        ];

        foreach ($categories as $cat){
            app()->setLocale('ro');
            $category = Category::create([
                'in_menu' => true,
                'title' => $cat[app()->getLocale()]['title'],
                'slug' => $cat[app()->getLocale()]['slug'],
                'old_number' => $cat[app()->getLocale()]['old_number']
            ]);

            app()->setLocale('en');
            $category->update($cat[app()->getLocale()]);

            app()->setLocale('ru');
            $category->update($cat[app()->getLocale()]);
        }
//        foreach (config('translatable.locales') as $locale){
//
//
//            $response = Http::get('https://deschide.md/api/sections?items_per_page=20&language='.$locale);
//            $apiSections = $response->json();
//            foreach ($apiSections['items'] as $item){
//                $category = Category::where('old_number',$item['number'])->first();
//                if (!$category){
//                    $category = Category::create([
//                        'title' => ucfirst($item['title']),
//                        'slug' => Str::slug($item['title']),
//                        'old_number' => $item['number'],
//                        'in_menu' => true
//                    ]);
//                }
//
//                if(!$category->hasTranslation($locale) && $item['number']===$category->old_number){
//                    app()->setLocale($locale);
//                    $category->update([
//                        'title' => ucfirst($item['title']),
//                        'slug' => Str::slug($item['title'])
//                    ]);
//                }
//
//            }
//
//        }
    }
}
