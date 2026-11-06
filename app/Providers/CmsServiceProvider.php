<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use App\Models\Menu;

class CmsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            View::share('siteSettings', Setting::all()->pluck('value', 'key')->toArray());
        }

        if (Schema::hasTable('menus')) {
            View::share('headerMenu', Menu::where('location', 'header')->with('items.children')->first());
        }
    }
}
