<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefcStaff extends Model
{
    use HasFactory;

    protected $table = 'tefc_staff';

    protected $fillable = [
        'centre_code',
        'name',
        'age',
        'designation',
        'education',
        'experience_years',
        'email',
        'phone',
        'status',
        'joining_date',
    ];

    protected $casts = [
        'age' => 'integer',
        'experience_years' => 'integer',
        'joining_date' => 'date',
    ];
}
