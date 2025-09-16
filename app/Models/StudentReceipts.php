<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StudentReceipts extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'term',
        'session',
        'tuition',
        'uniform',
        'exam_fee',
        'discount',
        'total_expected',
        'amount_paid',
        'amount_due',
        'school_type',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(StudentPayments::class, 'receipt_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::created(function ($receipt) {
           if ($receipt->amount_paid > 0) {
                $receipt->payments()->create([
                    'student_id'     => $receipt->student_id,
                    'amount_paid'         => $receipt->amount_paid, // ✅ use correct column
                    'payment_date'   => now(),
                    'payment_method' => 'cash',
                ]);
            }
        });

        static::updated(function ($receipt) {
            if ($receipt->wasChanged('amount_paid')) {
                $totalAlready = $receipt->payments()->sum('amount_paid'); // ✅
                $newPaid      = $receipt->amount_paid - $totalAlready;

                if ($newPaid > 0) {
                    $receipt->payments()->create([
                        'student_id'     => $receipt->student_id,
                        'amount_paid'         => $newPaid, // ✅
                        'payment_date'   => now(),
                        'payment_method' => 'cash',
                    ]);
                }
            }
        });
    }

}
