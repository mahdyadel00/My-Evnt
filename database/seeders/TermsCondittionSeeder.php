<?php

namespace Database\Seeders;

use App\Models\TermsCondittion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermsCondittionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TermsCondittion::create([
            'terms_condition'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Donec auctor, nunc nec',
            'privacy_policy'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Donec auctor, nunc nec',
            'refund_exchange'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Donec auctor, nunc nec',
            'shipping_payment'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec nunc ultricies ultricies. Donec auctor, nunc nec',
        ]);
    }
}
