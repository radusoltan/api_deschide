<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence();
        return [
            'category_id' => fake()->randomKey(Category::pluck('id','id')->all()),
            'title' => $title,
            'slug' => Str::slug($title),
            'lead' => fake()->paragraph(),
            'body' => fake()->paragraph(6)
        ];
    }
}
