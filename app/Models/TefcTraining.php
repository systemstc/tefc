<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefcTraining extends Model
{
    use HasFactory;

    protected $table = 'tefc_trainings';

    protected $fillable = [
        'centre_code',
        'title',
        'type',
        'event_date',
        'venue',
        'participants_count',
        'topics_covered',
    ];

    protected $casts = [
        'event_date' => 'date',
        'participants_count' => 'integer',
    ];
}
