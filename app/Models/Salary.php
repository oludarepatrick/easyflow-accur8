<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id', 'basic', 'bonus', 'loan_repayment',
        'health', 'gross', 'net_pay'
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'id');
    }
}


