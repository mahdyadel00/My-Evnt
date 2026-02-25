<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artical>
 */
class ArticalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'blog_id'       =>  Blog::factory(),
            'title'         =>  $this->faker->sentence,
            'description'   => $this->faker->paragraph,
        ];
    }
}
