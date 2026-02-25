<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'                      => $this->faker->randomElement(['bank_transfer', 'pay_online', 'cache']),
            'phone'                     => $this->faker->phoneNumber,
            'account_number'            => $this->faker->bankAccountNumber,
            'account_name'              => $this->faker->name,
            'bank_name'                 => $this->faker->company,
            'branch'                    => $this->faker->company,
            'postal_code'               => $this->faker->postcode,
            'iban'                      => $this->faker->iban('NG'),
            'card_number'               => $this->faker->creditCardNumber,
            'card_name'                 => $this->faker->name,
            'card_expiry'               => $this->faker->creditCardExpirationDate,
            'card_cvc'                  => $this->faker->randomNumber(3),
            'address'                   => $this->faker->address,
            'email'                     => $this->faker->unique()->safeEmail,
            'note'                      => $this->faker->sentence,
            'company_id'                =>  Company::factory(),
            'event_id'                  =>  1,
            'country_id'                =>  Country::factory(),
            ];
    }
}
