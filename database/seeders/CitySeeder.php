<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::create([
            'country_id'            => 1,
            'name'                  => 'Cairo',
            'is_available'          => 1,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }
}
