<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::updateOrCreate(
            ['schoolname' => 'Easyflow Schools'], // use your default school name
            [
                'email' => 'account@easyflowcollege.com.ng',
                'phone' => '+2348012345678',
                'address' => 'Plot 7, Lekan Oyekunle Str, Meiran Bustop, Agbado-Ijaye, Lagos.',
                'logo_url' => '/images/logo.png',

                'bank1' => 'GTBank',
                'accountname1' => 'Easyflow Schools',
                'accountno1' => '0123456789',

                'bank2' => 'Access Bank',
                'accountname2' => 'Easyflow Schools',
                'accountno2' => '1234567890',

                'bank3' => 'UBA',
                'accountname3' => 'Easyflow Schools',
                'accountno3' => '9876543210',

                'term' => 'First Term',
                'session' => '2025/2026',
            ]
        );
    }
}
