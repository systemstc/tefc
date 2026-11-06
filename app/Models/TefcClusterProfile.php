<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefcClusterProfile extends Model
{
    use HasFactory;

    protected $table = 'tefc_cluster_profiles';

    protected $fillable = [
        'centre_code',
        'cluster_name',
        'state',
        'covered_districts',
        'total_target_msmes',
        'specialization',
    ];

    protected $casts = [
        'total_target_msmes' => 'integer',
    ];
}
