<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'            =>  $this->faker->firstName,
            'middle_name'           =>  $this->faker->optional()->firstName,
            'last_name'             =>  $this->faker->lastName,
            'user_name'             =>  $this->faker->unique()->userName,
            'email'                 =>  $this->faker->unique()->safeEmail,
            'email_verified_at'     => now(),
            'phone'                 =>  $this->faker->optional()->phoneNumber,
            'address'               =>  $this->faker->optional()->address,
            'birth_date'            =>  $this->faker->optional()->date(),
            'password'              => '$2y$10$7Z6zQ',
            'api_token'              => $this->faker->optional()->numberBetween(00000, 99999),
            'about'                 => $this->faker->optional()->sentence,
            'last_login'            => $this->faker->optional()->dateTime(),
            'login_count'           => $this->faker->optional()->numberBetween(0, 100),
            'remember_token'        => Str::random(10),
            'city_id'               => City::factory(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
