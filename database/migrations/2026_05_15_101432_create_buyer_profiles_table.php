<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buyer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->text('profile_description')->nullable();
            $table->text('sourcing_requirements')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('country')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyer_profiles');
    }
};
