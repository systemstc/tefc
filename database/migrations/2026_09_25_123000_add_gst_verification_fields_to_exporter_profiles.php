<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exporter_profiles', function (Blueprint $table) {
            $table->string('gst_legal_name')->nullable()->after('gst_no');
            $table->string('gst_trade_name')->nullable()->after('gst_legal_name');
            $table->string('gst_constitution')->nullable()->after('gst_trade_name');
            $table->string('gst_status', 50)->nullable()->after('gst_constitution');
            $table->json('gst_signatories')->nullable()->after('gst_status');
            $table->boolean('gst_ownership_verified')->default(false)->after('gst_signatories');
            $table->string('gst_matched_signatory')->nullable()->after('gst_ownership_verified');
        });
    }

    public function down(): void
    {
        Schema::table('exporter_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'gst_legal_name',
                'gst_trade_name',
                'gst_constitution',
                'gst_status',
                'gst_signatories',
                'gst_ownership_verified',
                'gst_matched_signatory',
            ]);
        });
    }
};
