<?php

namespace Database\Seeders;

use App\Models\UserTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserTicket::create([
            'user_id'   => 1,
            'ticket_id' => 1,
            'quantity'  => 1,
        ]);
    }
}
