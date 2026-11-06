<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pages table
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug', 191)->unique();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Sections table (linked to pages)
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('type'); 
            $table->string('title')->nullable();
            $table->json('content'); 
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Menus
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('location', 191)->unique();
            $table->timestamps();
        });

        // Menu Items
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->string('url')->nullable();
            $table->string('page_slug', 191)->nullable();
            $table->integer('order')->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('pages');
    }
};
