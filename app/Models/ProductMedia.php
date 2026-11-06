<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'file_path', 'media_type'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
