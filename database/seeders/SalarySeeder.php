<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffMembers = \App\Models\User::where('category', 'staff')->take(5)->get();

        foreach ($staffMembers as $staff) {
            $basic = 50000;
            $bonus = rand(5000, 10000);
            $loan  = rand(0, 2000);
            $health = 1500;

            $gross = $basic + $bonus;
            $net = $gross - ($loan + $health);

            \App\Models\Salary::create([
                'staff_id'       => $staff->id,
                'basic'          => $basic,
                'bonus'          => $bonus,
                'loan_repayment' => $loan,
                'health'         => $health,
                'gross'          => $gross,
                'net_pay'        => $net,
            ]);
        }
    }
}
