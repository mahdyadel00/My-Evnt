<?php

namespace Database\Seeders;

use App\Models\AdFee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdFee::create([
            'name' => 'Basic',
            'description' => 'Basic ad fee',
            'price' => 10.00,
            'duration' => 30,
        ]);

        AdFee::create([
            'name' => 'Standard',
            'description' => 'Standard ad fee',
            'price' => 20.00,
            'duration' => 60,
        ]);

        AdFee::create([
            'name' => 'Premium',
            'description' => 'Premium ad fee',
            'price' => 30.00,
            'duration' => 90,
        ]);
    }
}
