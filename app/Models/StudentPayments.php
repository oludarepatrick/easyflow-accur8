<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPayments extends Model
{
   protected $fillable = [
        'receipt_id',
        'amount_paid',
        'payment_date',
        'payment_method',
    ];

    protected $casts = [
    'payment_date' => 'datetime',
    ];

    public function receipt()
    {
        return $this->belongsTo(StudentReceipts::class, 'receipt_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
