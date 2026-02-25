<?php

namespace Database\Seeders;

use App\Models\FormServay;
use Illuminate\Database\Seeder;

class FormServaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormServay::create([
            'event_id'                      => 1,
            'first_name'                    => 'John',
            'last_name'                     => 'Doe',
            'email'                         => 'john@doe.com',
            'phone'                         => '01234567890',
            'session_type'                  => 'kids',
            'quantity'                      => 1,
            'unit_price'                    => 0,
            'total_amount'                  => 0,
            'notes'                         => 'test',
            'status'                        => 'pending',
            'date'                          => '2025-07-02',
            'time'                          => '10:00',
            'address'                       => '123 Main St',

        ]);
    }
}
