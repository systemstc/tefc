<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextileCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function subcategories()
    {
        return $this->hasMany(TextileSubcategory::class, 'category_id')->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasManyThrough(TextileProduct::class, TextileSubcategory::class, 'category_id', 'subcategory_id');
    }

    public function attributeRules()
    {
        return $this->hasMany(CategoryAttributeRule::class, 'category_id')->orderBy('sort_order');
    }

    public function catalogueProducts()
    {
        return $this->hasMany(Product::class, 'textile_category_id');
    }
}
