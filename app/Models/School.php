<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'schoolname',
        'email',
        'phone',
        'address',
        'logo_url',
        'bank1', 'accountname1', 'accountno1',
        'bank2', 'accountname2', 'accountno2',
        'bank3', 'accountname3', 'accountno3',
        'term',
        'session',
    ];
}
