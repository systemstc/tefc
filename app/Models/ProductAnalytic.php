<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAnalytic extends Model
{
    protected $fillable = ['product_id', 'session_id', 'user_id', 'type', 'interaction_type', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
