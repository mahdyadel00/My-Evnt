<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = Setting::create([
            'email'                 => 'event@email.com',
            'facebook'              => 'facebook.com',
            'twitter'               => 'twitter.com',
            'instagram'             => 'instagram.com',
            'youtube'               => 'youtube.com',
            'phone'                 => '01122907742',
            'phone_2'               => '01065839463',
            'whats_app'             => '01065839463',
            'name'                  => 'Event',
            'description'           => 'Event',
        ]);
    }
}
