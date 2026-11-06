<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Optional if buyer is logged in
            $table->string('type'); // 'view', 'interaction'
            $table->string('interaction_type')->nullable(); // 'media_click', 'brochure_download', etc.
            $table->json('metadata')->nullable(); // For additional info
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_analytics');
    }
};
