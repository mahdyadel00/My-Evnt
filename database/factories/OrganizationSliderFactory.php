<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationSlider>
 */
class OrganizationSliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'         =>  $this->faker->sentence,
            'description'   =>  $this->faker->paragraph,
            'video'         =>  $this->faker->url,
        ];
    }
}
