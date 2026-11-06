<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['menu_id', 'label', 'url', 'page_slug', 'order', 'parent_id'];

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function getFinalUrlAttribute()
    {
        if ($this->page_slug) {
            return url('/p/' . $this->page_slug);
        }
        
        // Ensure relative URLs are handled by Laravel's url helper
        return $this->url ? url($this->url) : '#';
    }
}
