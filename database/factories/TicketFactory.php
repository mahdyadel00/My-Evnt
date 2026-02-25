<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id'      => Event::factory(),
            'ticket_type'   => $this->faker->word,
            'price'         => $this->faker->randomFloat(2, 0, 1000),
//            'discount'      => $this->faker->randomFloat(2, 0, 100),
            'quantity'      => $this->faker->numberBetween(1, 100),
            'description'   => $this->faker->sentence,
        ];
    }
}
