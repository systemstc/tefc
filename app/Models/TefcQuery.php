<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefcQuery extends Model
{
    use HasFactory;

    protected $table = 'tefc_queries';

    protected $fillable = [
        'centre_code',
        'company_name',
        'contact_person',
        'subject',
        'category',
        'received_date',
        'resolved_date',
        'status',
        'is_within_sla',
        'resolution_notes',
    ];

    protected $casts = [
        'received_date' => 'date',
        'resolved_date' => 'date',
        'is_within_sla' => 'boolean',
    ];
}
