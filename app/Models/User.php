<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\StaffBankDetail;
use App\Models\StaffSalary;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_CLERK = 'clerk';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'class',
        'email',
        'phone',
        'term',
        'session',
        'role',
        'category',
        'schooltype',
        'status',
        

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isClerk(): bool
    {
        return $this->role === self::ROLE_CLERK;
    }

     use HasFactory, Notifiable;

    // relationships
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }


    public function receipts()
    {
        return $this->hasMany(StudentReceipts::class, 'student_id');
    }

    public function activeReceipt()
    {
        $school = School::first(); // get current term & session
        return $this->hasOne(StudentReceipts::class, 'student_id')
            ->where('term', $school->term)
            ->where('session', $school->session);
    }

    public function bankDetail()
    {
        return $this->hasOne(StaffBankDetail::class, 'staff_id');
    }

    public function salaries()
    {
        return $this->hasOne(StaffSalary::class, 'staff_id');
    }

}
