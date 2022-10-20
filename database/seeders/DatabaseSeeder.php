<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            CategoryTableSeeder::class,
            AuthorsTableSeeder::class,
//            ArticleTableSeeder::class,
            RenditionTableSeeder::class
        ]);
    }
}
