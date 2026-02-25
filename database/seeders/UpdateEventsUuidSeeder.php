<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Str;

class UpdateEventsUuidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::whereNull('uuid')->chunk(100, function ($events) {
            foreach ($events as $event) {
                $event->uuid = Str::uuid()->toString();
                $event->save();
            }
        });
    }
}
