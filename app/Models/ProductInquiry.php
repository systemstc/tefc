<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInquiry extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'buyer_name', 'buyer_email', 'buyer_phone', 'message', 'status',
        'quantity_required', 'category', 'material', 'pattern', 'width_size', 'additional_requirements'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
