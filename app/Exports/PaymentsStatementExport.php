<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsStatementExport implements FromCollection, WithHeadings
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments->map(function ($p) {
            return [
                'Date' => $p->payment_date ? $p->payment_date->format('Y-m-d H:i:s') : '',
                'Student' => $p->receipt->student->firstname . ' ' . $p->receipt->student->lastname,
                'Class' => $p->receipt->student->class ?? '',
                'Term' => $p->receipt->term,
                'Session' => $p->receipt->session,
                'Amount Paid' => $p->amount_paid,
                'Method' => $p->payment_method,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date','Student','Class','Term','Session','Amount Paid','Method'];
    }
}
