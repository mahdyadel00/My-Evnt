<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'         => $this->faker->word,
            'description'   => $this->faker->sentence,
            'price_monthly' => $this->faker->randomFloat(2, 0, 100),
            'price_yearly'  => $this->faker->randomFloat(2, 0, 100),
            'discount'      => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
