<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user if not already exists
        User::firstOrCreate(
            ['email' => 'easy_admin@school.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password@123'), // 👈 change to a secure password
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Optional: Create a sample clerk
        User::firstOrCreate(
            ['email' => 'easy_clerk@school.com'],
            [
                'name' => 'Account Clerk',
                'password' => Hash::make('password@123'),
                'role' => User::ROLE_CLERK,
            ]
        );
    }
}
