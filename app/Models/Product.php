<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'created_by_user_id', 'acknowledged_at', 'acknowledged_by',
        'textile_category_id', 'textile_subcategory_id', 'textile_product_id',
        'name', 'category', 'cluster', 'contact_information', 'hs_code', 'material_composition', 
        'production_capacity', 'width_size', 'moq', 'unit_of_measurement', 'tags',
        'pattern', 'color', 'technical_specifications', 'certifications', 
        'status', 'is_published', 'moderation_notes'
    ];

    protected $casts = [
        'category' => 'array',
        'acknowledged_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function textileCategory()
    {
        return $this->belongsTo(TextileCategory::class, 'textile_category_id');
    }

    public function textileSubcategory()
    {
        return $this->belongsTo(TextileSubcategory::class, 'textile_subcategory_id');
    }

    public function textileProduct()
    {
        return $this->belongsTo(TextileProduct::class, 'textile_product_id');
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class)->orderBy('sort_order');
    }

    public function fibres()
    {
        return $this->hasMany(ProductAttributeValue::class)->where('attribute_type', 'fibre');
    }

    public function yarns()
    {
        return $this->hasMany(ProductAttributeValue::class)->where('attribute_type', 'yarn');
    }

    public function fabrics()
    {
        return $this->hasMany(ProductAttributeValue::class)->where('attribute_type', 'fabric');
    }

    public function processes()
    {
        return $this->hasMany(ProductAttributeValue::class)->where('attribute_type', 'process');
    }

    public function getPrimaryMediaAttribute()
    {
        return $this->media->where('is_primary', true)->first() ?? $this->media->first();
    }

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
