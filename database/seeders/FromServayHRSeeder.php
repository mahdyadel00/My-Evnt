<?php

namespace Database\Seeders;

use App\Models\FromServayHR;
use Illuminate\Database\Seeder;

class FromServayHRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FromServayHR::create([
            'event_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'company_name' => 'Test Company',
            'position' => 'Test Position',
            'notes' => 'This is a test note',
            'status' => 'approved',
        ]);
    }
}
