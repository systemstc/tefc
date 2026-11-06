<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exporter_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->string('iec_code')->nullable();
            $table->text('profile_description')->nullable();
            $table->string('hs_codes')->nullable(); // comma-separated or JSON
            $table->string('production_capacity')->nullable();
            $table->string('certifications')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exporter_profiles');
    }
};
