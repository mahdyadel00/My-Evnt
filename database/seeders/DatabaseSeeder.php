<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            UserSeeder::class,
            TenantSeeder::class,
            SettingSeeder::class,
            WebinarSeeder::class,
        ]);
    }
}
