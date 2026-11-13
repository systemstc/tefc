<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttributeRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'attribute_type',
        'requirement_level',
        'is_multiple',
        'help_text',
        'sort_order',
    ];

    protected $casts = [
        'is_multiple' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(TextileCategory::class, 'category_id');
    }
}
