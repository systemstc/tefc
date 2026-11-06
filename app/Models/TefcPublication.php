<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefcPublication extends Model
{
    use HasFactory;

    protected $table = 'tefc_publications';

    protected $fillable = [
        'centre_code',
        'title',
        'type',
        'issue_date',
        'summary',
        'file_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];
}
