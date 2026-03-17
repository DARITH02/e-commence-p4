<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin Role
        $superAdminRole = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            ['name' => 'Super Administrator', 'description' => 'System owner with unrestricted access, including deletions.']
        );

        // Create Admin Role
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrator', 'description' => 'System administrator with management access, but restricted deletions.']
        );

        // Create Customer Role (Existing)
        Role::firstOrCreate(
            ['slug' => 'customer'],
            ['name' => 'Customer', 'description' => 'Standard customer account.']
        );

        // Create Super Admin User
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@ecommerce.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->roles()->syncWithoutDetaching([$superAdminRole->id]);

        // Create a test Admin User (Restricted)
        $adminUser = User::firstOrCreate(
            ['email' => 'staff@ecommerce.com'],
            [
                'name' => 'Staff Admin',
                'password' => Hash::make('staff123'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
