<?php

namespace Database\Seeders;

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
                    'slug' => Str::slug('Politic')
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

                ],
                'ru' => [
                    'title' => 'Общество',
                    'slug' => Str::slug('Общество'),
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

                ],
                'ru' => [
                    'title' => 'Экономика',
                    'slug' => Str::slug('Экономика'),
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

                ],
                'ru' => [
                    'title' => 'Культура',
                    'slug' => Str::slug('Культура'),
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

                ],
                'ru' => [
                    'title' => 'Спорт',
                    'slug' => Str::slug('Спорт'),
                ],
                'en' => [
                    'title' => 'Sport',
                    'slug' => Str::slug('Sport')
                ]
            ]

        ];

        foreach ($categories as $cat){
            app()->setLocale('ro');
            $category = Category::create([
                'in_menu' => true,
                'title' => $cat[app()->getLocale()]['title'],
                'slug' => $cat[app()->getLocale()]['slug']
            ]);

            app()->setLocale('en');
            $category->update($cat[app()->getLocale()]);

            app()->setLocale('ru');
            $category->update($cat[app()->getLocale()]);
        }
    }
}
