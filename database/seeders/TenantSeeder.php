<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [['name' => 'Tenant 1', 'subdomain' => 'tenant1'], ['name' => 'Tenant 2', 'subdomain' => 'tenant2']];

        foreach ($tenants as $tenant) {
            Tenant::firstOrCreate(['subdomain' => $tenant['subdomain']], ['name' => $tenant['name']]);
        }
    }
}
