<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_type',
        'master_id',
        'attribute_name',
        'attribute_value',
        'percentage',
        'sort_order',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
