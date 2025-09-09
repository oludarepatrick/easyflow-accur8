<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = \App\Models\User::where('category', 'student')->take(5)->get();

        foreach ($students as $student) {
            $invoice = \App\Models\Invoice::create([
                'student_id'   => $student->id,
                'class'        => 'JSS1',
                'term'         => 'first',
                'session'      => '2024/2025',
                'total_amount' => 0, // will calculate
                'status'       => 'paid',
            ]);

            $items = [
                ['fee_type' => 'tuition', 'amount' => 20000],
                ['fee_type' => 'uniform', 'amount' => 5000],
                ['fee_type' => 'exam_fee', 'amount' => 3000],
            ];

            $total = 0;
            foreach ($items as $item) {
                $invoice->items()->create($item);
                $total += $item['amount'];
            }

            $invoice->update(['total_amount' => $total]);
        }
    }
}
