<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get the super_admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['guard_name' => 'web']
        );

        // Create or get the panel_user role
        $panelUserRole = Role::firstOrCreate(
            ['name' => 'panel_user'],
            ['guard_name' => 'web']
        );

        // Create regular user
        $regularUser = User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('12345678'),
                'avatar_url' => null,
            ]
        );

        // Assign panel_user role to regular user
        if (!$regularUser->hasRole($panelUserRole)) {
            $regularUser->assignRole($panelUserRole);
        }

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('87654321'),
                'avatar_url' => null,
            ]
        );

        // Assign super_admin role to admin user
        if (!$adminUser->hasRole($superAdminRole)) {
            $adminUser->assignRole($superAdminRole);
        }
    }
}