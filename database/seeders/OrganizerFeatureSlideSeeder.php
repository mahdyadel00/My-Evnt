<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\OrganizerFeatureSlide;
use Illuminate\Database\Seeder;

class OrganizerFeatureSlideSeeder extends Seeder
{
    /**
     * Default homepage organizer carousel slides (title, description, image only).
     */
    public function run(): void
    {
        if (OrganizerFeatureSlide::query()->exists()) {
            return;
        }

        $rows = [
            [
                'sort_order' => 0,
                'title' => 'Are You an Organizer?',
                'subtitle' => 'Transform your event management experience with our powerful platform. Easily create and customize your events, manage tickets, and engage with your audience — all from a simple and intuitive dashboard.',
                'hero_image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=550&h=400&fit=crop',
            ],
            [
                'sort_order' => 1,
                'title' => 'Sell Tickets Effortlessly',
                'subtitle' => 'Set up multiple ticket tiers, apply early-bird discounts, and track real-time sales analytics. Our secure payment gateway supports every major method so you never miss a buyer.',
                'hero_image' => 'https://images.unsplash.com/photo-1559223607-b4d0555ae227?w=550&h=400&fit=crop',
            ],
            [
                'sort_order' => 2,
                'title' => 'Engage Your Audience',
                'subtitle' => 'Built-in email campaigns, social sharing tools, and discount codes help you grow attendance. Reach the right people at the right time with targeted notifications and automated reminders.',
                'hero_image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=550&h=400&fit=crop',
            ],
            [
                'sort_order' => 3,
                'title' => 'Data-Driven Decisions',
                'subtitle' => 'Monitor registrations, revenue, and attendee demographics in real time. Detailed post-event reports help you understand what worked and optimise every future event for maximum impact.',
                'hero_image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=550&h=400&fit=crop',
            ],
        ];

        foreach ($rows as $row) {
            OrganizerFeatureSlide::query()->create(array_merge(['is_active' => true], $row));
        }
    }
}
