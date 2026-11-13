<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextileProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcategory_id',
        'name',
        'slug',
        'default_hs_code',
        'synonyms',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function subcategory()
    {
        return $this->belongsTo(TextileSubcategory::class, 'subcategory_id');
    }

    public function category()
    {
        return $this->hasOneThrough(TextileCategory::class, TextileSubcategory::class, 'id', 'id', 'subcategory_id', 'category_id');
    }

    public function catalogueProducts()
    {
        return $this->hasMany(Product::class, 'textile_product_id');
    }

    public function getFullBreadcrumbAttribute(): string
    {
        $cat = $this->subcategory?->category?->name ?? '';
        $sub = $this->subcategory?->name ?? '';
        return "{$cat} > {$sub} > {$this->name}";
    }
}
