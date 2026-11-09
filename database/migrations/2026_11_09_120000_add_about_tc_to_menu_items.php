<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;
use App\Models\MenuItem;

return new class extends Migration
{
    public function up(): void
    {
        $headerMenu = Menu::where('location', 'header')->first();
        if ($headerMenu) {
            $existing = MenuItem::where('menu_id', $headerMenu->id)
                ->where('label', 'like', 'About TC%')
                ->first();

            if (!$existing) {
                // Shift existing items with order >= 3 up by 1
                MenuItem::where('menu_id', $headerMenu->id)
                    ->where('order', '>=', 3)
                    ->increment('order');

                MenuItem::create([
                    'menu_id' => $headerMenu->id,
                    'label' => 'About TC',
                    'url' => 'https://textilescommittee.gov.in',
                    'page_slug' => null,
                    'order' => 3,
                ]);
            }
        }
    }

    public function down(): void
    {
        $headerMenu = Menu::where('location', 'header')->first();
        if ($headerMenu) {
            $deleted = MenuItem::where('menu_id', $headerMenu->id)
                ->where('label', 'like', 'About TC%')
                ->delete();

            if ($deleted) {
                MenuItem::where('menu_id', $headerMenu->id)
                    ->where('order', '>', 3)
                    ->decrement('order');
            }
        }
    }
};
