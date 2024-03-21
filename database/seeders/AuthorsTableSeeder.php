<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Author;
use Faker\Factory as Faker;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        app()->setLocale('ro');
        $author = Author::create([
            'email' => 'deschide@author.com',
            'facebook' => 'DeschideMD',
            'first_name' => 'Deschide',
            'last_name' => 'Stirea',
            'full_name' => 'Deschide Stirea',
            'slug' => Str::slug('Deschide Stirea')
        ]);

        app()->setLocale('en');
        $author->update([
            'first_name' => 'Deschide',
            'last_name' => 'News',
            'full_name' => 'Deschide News',
            'slug' => Str::slug('Deschide News')
        ]);

        app()->setlocale('ru');
        $author->update([
            'first_name' => 'Deschide',
            'last_name' => 'Новости',
            'full_name' => 'Deschide Новости',
            'slug' => Str::slug('Deschide Новости')
        ]);

//        for ($i=0;$i<100;$i++){
//
//            app()->setLocale('ro');
//
//            $email = $faker->unique()->safeEmail;
//            $firstName = $faker->firstName;
//            $lastName = $faker->lastName;
//            $facebook = Str::slug($firstName.' '.$lastName);
//            $fullName = $firstName.' '.$lastName;
//
//            Author::create([
//                'email' => $email,
//                'facebook' => $facebook,
//                'first_name' => $firstName,
//                'last_name' => $lastName,
//                'full_name' => $fullName,
//                'slug' => Str::slug($fullName)
//            ]);
//
//        }
    }
}
