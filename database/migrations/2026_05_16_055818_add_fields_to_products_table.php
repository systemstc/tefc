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
        Schema::table('products', function (Blueprint $table) {
            $table->string('cluster')->nullable()->after('category');
            $table->text('contact_information')->nullable()->after('cluster');
            $table->text('category')->change(); // Change category to text to hold JSON if it gets long
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cluster', 'contact_information']);
            $table->string('category')->change();
        });
    }
};
