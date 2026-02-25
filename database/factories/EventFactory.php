<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Company;
use App\Models\Currency;
use App\Models\EventCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => EventCategory::factory(),
            'sub_category_id' => EventCategory::factory(),
            'city_id' => City::factory(),
            'currency_id' => Currency::factory(),
            'company_id' => Company::factory(),
            'ad_fee_id' => 1,
            'name' => $this->faker->name,
            'location' => $this->faker->address,
            'description' => $this->faker->text,
            'address' => $this->faker->address,
            'format' => $this->faker->boolean,
            'days' => $this->faker->words,
            'is_active' => $this->faker->boolean,
            'price' => $this->faker->randomFloat(2, 0, 999999),
            'cancellation_policy' => $this->faker->text,
            'qr_code' => $this->faker->word,
        ];
    }
}
