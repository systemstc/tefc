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
        Schema::table('exporter_profiles', function (Blueprint $table) {
            $table->string('tefc_centre')->nullable()->after('state');
            $table->text('rejection_reason')->nullable()->after('remarks');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('tefc_centre')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exporter_profiles', function (Blueprint $table) {
            $table->dropColumn(['tefc_centre', 'rejection_reason']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tefc_centre']);
        });
    }
};
