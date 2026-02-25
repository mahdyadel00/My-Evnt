<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::create([
            'name'              => 'Argentina',
            'code'              => 'AR',
            'is_available'      => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
