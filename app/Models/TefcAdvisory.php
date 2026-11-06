<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefcAdvisory extends Model
{
    use HasFactory;

    protected $table = 'tefc_advisories';

    protected $fillable = [
        'centre_code',
        'user_id',
        'company_name',
        'iec_status',
        'session_date',
        'advisory_topic',
        'outcome_notes',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
