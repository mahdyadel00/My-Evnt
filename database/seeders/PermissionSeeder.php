<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'view setting',
            'create setting',
            'update setting',
            'delete setting',

            'view category',
            'create category',
            'update category',
            'delete category',

            'view user',
            'create user',
            'update user',
            'delete user',

            'view role',
            'create role',
            'update role',
            'delete role',

            'view sliders',
            'create sliders',
            'update sliders',
            'delete sliders',

            'view country',
            'create country',
            'update country',
            'delete country',

            'view city',
            'create city',
            'update city',
            'delete city',

            'view company',
            'create company',
            'update company',
            'delete company',

            'view customer',
            'create customer',
            'update customer',
            'delete customer',

            'view blog',
            'create blog',
            'update blog',
            'delete blog',

            'view article',
            'create article',
            'update article',
            'delete article',

            'view faq',
            'create faq',
            'update faq',
            'delete faq',

            'view event_category',
            'create event_category',
            'update event_category',
            'delete event_category',

            'view event',
            'create event',
            'update event',
            'delete event',

            'view ad_fee',
            'create ad_fee',
            'update ad_fee',
            'delete ad_fee',

            'view slider',
            'create slider',
            'update slider',
            'delete slider',

            'view survey',
            'create survey',
            'update survey',
            'delete survey',

            'view survey_hr',
            'create survey_hr',
            'update survey_hr',
            'delete survey_hr',

            'view sold_out',
            'update sold_out',
            'delete sold_out',

            'view social_gallery',
            'update social_gallery',

            'view partners',
            'create partners',
            'update partners',
            'delete partners',

            'view orders',
            'create orders',
            'update orders',    
            'delete orders',

            'view reports',
            'create reports',
            'update reports',
            'delete reports',

            'view webinars',
            'create webinars',
            'update webinars',
            'delete webinars',

            'view sold_out_webinar',
            'update sold_out_webinar',
            'delete sold_out_webinar',

            'view about_webinars',
            'create about_webinars',
            'update about_webinars',
            'delete about_webinars',

            'view webinar_speakers',
            'create webinar_speakers',
            'update webinar_speakers',
            'delete webinar_speakers',

            'view webinar_cards',
            'create webinar_cards',
            'update webinar_cards',
            'delete webinar_cards',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
