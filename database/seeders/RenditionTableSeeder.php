<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rendition;

class RenditionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $renditions = [
            [
                'name' => 'main',
                'width' => 1000,
                'height' => 550,
                'aspect' => 1.818181818181818,
                'coords' => json_encode([])
            ],
            [
                'name' => 'mobileMain',
                'width' => 1000,
                'height' => 777,
                'aspect' => 1.28,
                'coords' => json_encode([])
            ]
        ];

        foreach ($renditions as $rendition){

            Rendition::create($rendition);

        }

    }
}
