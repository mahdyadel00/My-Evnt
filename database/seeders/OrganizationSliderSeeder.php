<?php

namespace Database\Seeders;

use App\Models\OrganizationSlider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationSlider::create()->factory(1)->create();
    }
}
