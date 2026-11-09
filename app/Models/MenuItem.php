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

        if (!$this->url) {
            return '#';
        }

        // External URLs starting with http://, https://, or //
        if (preg_match('/^(https?:\/\/|\/\/)/i', $this->url)) {
            return $this->url;
        }

        // Domain-like external URLs without protocol
        if (preg_match('/^[a-z0-9-]+(\.[a-z0-9-]+)+/i', $this->url) && !str_starts_with($this->url, '/')) {
            return 'https://' . $this->url;
        }
        
        // Ensure relative URLs are handled by Laravel's url helper
        return url($this->url);
    }
}
