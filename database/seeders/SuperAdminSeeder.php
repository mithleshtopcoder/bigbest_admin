<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if SuperAdmin user already exists
        $superAdminEmail = 'superadmin@email.com';
        $user = User::where('email', $superAdminEmail)->first();

        if (!$user) {
            // Create SuperAdmin user
            $user = User::create([
                'name' => 'Super Admin',
                'uuid' => '123456789',
                'email' => $superAdminEmail,
                'password' => Hash::make('superadmin@123'), // Default password - should be changed after first login
                'email_verified_at' => now(),
            ]);

            $this->command->info('SuperAdmin user created successfully!');
            $this->command->info('Email: ' . $superAdminEmail);
            $this->command->info('Password: superadmin@123');
            $this->command->warn('Please change the password after first login!');
        } else {
            $this->command->info('SuperAdmin user already exists.');
        }

        // Get SuperAdmin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        if ($superAdminRole) {
            // Assign SuperAdmin role to user using Spatie's assignRole method
            // Spatie's assignRole accepts Role model, role name (string), or role ID
            // Spatie's hasRole method checks by role name or Role model
            if (!$user->hasRole($superAdminRole->name)) {
                $user->assignRole($superAdminRole);
                $this->command->info('SuperAdmin role assigned to user.');
            } else {
                $this->command->info('SuperAdmin role already assigned to user.');
            }
        } else {
            $this->command->error('SuperAdmin role not found! Please run RoleSeeder first.');
        }
    }
}