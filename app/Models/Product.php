<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'created_by_user_id', 'acknowledged_at', 'acknowledged_by',
        'name', 'category', 'cluster', 'contact_information', 'hs_code', 'material_composition', 
        'production_capacity', 'width_size', 'pattern', 'color', 'technical_specifications', 'certifications', 
        'status', 'moderation_notes'
    ];

    protected $casts = [
        'category' => 'array',
        'acknowledged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function acknowledger()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    public function isPendingExporterReview(): bool
    {
        return in_array($this->status, ['Pending Exporter Review', 'Pending']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }
}
