<?php

namespace Database\Seeders;

use App\Models\Artical;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artical::factory()->count(3)->create();
    }
}
