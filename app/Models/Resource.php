<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'category',
        'file_path',
        'file_type',
        'file_size',
        'is_active',
    ];
}
