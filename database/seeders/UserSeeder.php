<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'city_id'        => 1,
                'first_name'     => 'Super',
                'middle_name'    => 'Admin',
                'last_name'      => 'User',
                'user_name'      => 'superadmin',
                'phone'          => '1234567890',
                'email_verified_at' => now(),
                'password'       => Hash::make('Admin@1031996'),
                'api_token'      => 12345,
                'about'          => 'Super Admin User',
                'last_login'     => now(),
                'login_count'    => 1,
                'remember_token' => Str::random(10),
                'created_at'     => now(),
                'code'           => 12345,
                'is_active'      => 1,
            ]
        );

        $user_2 = User::firstOrCreate(
            ['email' => 'mahdyadel00@email.com'],
            [
                'city_id'        => 1,
                'first_name'     => 'Mahdy',
                'middle_name'    => 'Adel',
                'last_name'      => 'Mahdy',
                'user_name'      => 'mahdyadel',
                'phone'          => '1234567890',
                'email_verified_at' => now(),
                'password'       => Hash::make('Mahdy@1031996'),
                'api_token'      => 12345,
                'about'          => 'Teacher User',
                'last_login'     => now(),
                'login_count'    => 1,
                'remember_token' => Str::random(10),
                'created_at'     => now(),
                'code'           => 12345,
                'is_active'      => 1,
            ]
        );

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $role_teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $role->givePermissionTo($permission->name);
            }
            if (!$role_teacher->hasPermissionTo($permission->name)) {
                $role_teacher->givePermissionTo($permission->name);
            }
        }

        // Assign roles
        if (!$user->hasRole($role->name)) {
            $user->assignRole($role);
        }

        if (!$user_2->hasRole($role_teacher->name)) {
            $user_2->assignRole($role_teacher);
        }
    }
}
