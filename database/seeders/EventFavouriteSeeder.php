<?php

namespace Database\Seeders;

use App\Models\EventFavourite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventFavouriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventFavourite::factory()->count(1)->create();
    }
}
