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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assuming the seller is a user
            $table->string('name');
            $table->string('category');
            $table->string('hs_code')->nullable();
            $table->text('material_composition')->nullable();
            $table->string('production_capacity')->nullable();
            $table->text('technical_specifications')->nullable();
            $table->text('certifications')->nullable();
            $table->enum('status', ['Pending', 'Under Verification', 'Approved', 'Rejected'])->default('Pending');
            $table->text('moderation_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
