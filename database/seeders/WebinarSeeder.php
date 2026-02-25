<?php

namespace Database\Seeders;

use App\Models\Webinar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Webinar::create([
            'webinar_name'                  => 'Webinar 1',
            'company_name'                  => 'Company 1',
            'description'                   => 'Description 1',
            'title'                         => 'Title 1',
            'date'                          => '2025-01-01',
            'time'                          => '10:00:00',
            'link'                          => 'https://www.google.com',
            'status'                        => true,
            'facebook'                      => 'https://www.facebook.com',
            'linkedin'                      => 'https://www.linkedin.com',
            'instagram'                     => 'https://www.instagram.com',
            'youtube'                       => 'https://www.youtube.com',
        ]);
        Webinar::create([
            'webinar_name'                  => 'Webinar 2',
            'company_name'                  => 'Company 2',
            'description'                   => 'Description 2',
            'title'                         => 'Title 2',
            'date'                          => '2025-01-02',
            'time'                          => '11:00:00',
            'link'                          => 'https://www.google.com',
            'status'                        => true,
            'facebook'                      => 'https://www.facebook.com',
            'linkedin'                      => 'https://www.linkedin.com',
            'instagram'                     => 'https://www.instagram.com',
            'youtube'                       => 'https://www.youtube.com',
        ]);
        Webinar::create([
            'webinar_name'                  => 'Webinar 3',
            'company_name'                  => 'Company 3',
            'description'                   => 'Description 3',
            'title'                         => 'Title 3',
            'date'                          => '2025-01-03',
            'time'                          => '12:00:00',
            'link'                          => 'https://www.google.com',
            'status'                        => true,
            'facebook'                      => 'https://www.facebook.com',
            'linkedin'                      => 'https://www.linkedin.com',
            'instagram'                     => 'https://www.instagram.com',
            'youtube'                       => 'https://www.youtube.com',
        ]);
    }
}
