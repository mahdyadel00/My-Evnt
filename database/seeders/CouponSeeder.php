<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::create([
            'code'              => 'FIXED10',
            'type'              => 'fixed',
            'value'             => 10,
            'description'       => 'Get 10% discount on your order',
            'start_date'        => now(),
            'end_date'          => now()->addDays(7),
        ]);
    }
}
