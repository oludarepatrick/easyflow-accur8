<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->updateOrInsert(
            ['email' => 'easy_admin@easyflowcollege.com.ng'],
            [
                'name'       => 'Super Admin',
                'password'   => Hash::make('password@123'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('admins')->updateOrInsert(
            ['email' => 'easy_clerk@easyflowcollege.com.ng'],
            [
                'name'       => 'School Clerk',
                'password'   => Hash::make('password@456'),
                'role'       => 'clerk',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
