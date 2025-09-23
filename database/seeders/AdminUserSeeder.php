<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'easy_admin@easyflowcollege.com.ng',
                'password' => Hash::make('password@123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'School Clerk',
                'email' => 'easy_clerk@easyflowcollege.com.ng',
                'password' => Hash::make('password@456'),
                'role' => 'clerk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
