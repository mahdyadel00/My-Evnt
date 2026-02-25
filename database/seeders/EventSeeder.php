<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::create([
            'category_id' => 1,
            'sub_category_id' => 1,
            'city_id' => 1,
            'currency_id' => 1,
            'company_id' => 1,
            'name' => 'Event 1',
            'location' => 'Location 1',
            'description' => 'Description 1',
            'format' => 1,
            'address' => 'Address 1',
            'external_link' => 'https://www.google.com',
            'is_exclusive' => false,
        ]);

        $event->tickets()->create([
            'ticket_type' => 'VIP',
            'price' => 100,
            'quantity' => 1,
        ]);
    }
}
