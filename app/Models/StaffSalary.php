<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSalary extends Model
{
    protected $table = 'staff_salaries';

    protected $fillable = [
        'staff_id',
        'month',
        'year',
        'basic',
        'bonus',
        'loan_repayment',
        'health',
        'lesson_amount',
        'net_pay',
        'gross',
        'year',
        'status',
        'date_paid',
    ];

    protected $casts = [
        'date_paid' => 'date',
        'net_pay' => 'decimal:2',
        'gross' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
   