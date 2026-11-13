<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextileSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(TextileCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(TextileProduct::class, 'subcategory_id')->orderBy('sort_order');
    }

    public function catalogueProducts()
    {
        return $this->hasMany(Product::class, 'textile_subcategory_id');
    }
}
