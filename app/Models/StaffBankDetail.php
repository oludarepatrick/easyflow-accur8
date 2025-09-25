<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffBankDetail extends Model
{
    protected $table = 'staff_bank_details';

    protected $fillable = [
        'staff_id',
        'bank_name',
        'account_name',
        'account_no',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
