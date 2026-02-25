<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'    => $this->faker->firstName,
            'last_name'     => $this->faker->lastName,
            'user_name'     => $this->faker->userName,
            'company_name'  => $this->faker->company, // 'company_name' => 'company name
            'email'         => $this->faker->unique()->safeEmail,
            'phone'         => $this->faker->optional()->phoneNumber,
            'whats_app'     => $this->faker->optional()->phoneNumber,
            'password'      => $this->faker->password, // 'password' => 'password
            'website'       => $this->faker->optional()->url,
            'status'        => $this->faker->boolean(90),
            'description'   => $this->faker->optional()->realText(),
            'address'       => $this->faker->optional()->address,
            'is_active'     => $this->faker->boolean,
            'social_id'     => $this->faker->optional()->uuid,
            'social_type'   => $this->faker->optional()->word,
            'type'          => $this->faker->randomElement(['company', 'user']),
            'api_token'     => $this->faker->unique()->sha1,
        ];
    }
}
