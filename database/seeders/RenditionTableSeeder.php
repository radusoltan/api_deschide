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
                'width' => 1600,
                'height' => 900,
                'aspect' => 1.777777777777778,
                'coords' => json_encode([])
            ],
            [
                'name' => 'important',
                'width' => 1170,
                'height' => 450,
                'aspect' => 2.6,
                'coords' => json_encode([])
            ]
        ];

        foreach ($renditions as $rendition){

            Rendition::create($rendition);

        }

    }
}
