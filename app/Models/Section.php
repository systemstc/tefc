<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['page_id', 'type', 'title', 'content', 'order', 'is_active'];

    protected $casts = [
        'content' => 'json',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
