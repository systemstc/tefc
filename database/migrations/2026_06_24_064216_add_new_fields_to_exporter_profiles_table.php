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
            $table->text('factory_address')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->integer('year_of_establishment')->nullable();
            $table->string('textile_segment')->nullable();
            $table->string('main_product_hsn')->nullable();
            $table->string('installed_capacity')->nullable();
            $table->text('major_machineries')->nullable();
            $table->string('capacity_unit')->nullable();
            $table->string('monthly_production')->nullable();
            $table->string('annual_turnover')->nullable();
            $table->string('is_exporter', 1)->default('N'); // 'Y' or 'N'
            $table->string('export_turnover')->nullable();
            $table->text('major_exporting_countries')->nullable();
            $table->text('product_images')->nullable(); // JSON paths array
            $table->text('export_potential')->nullable();
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exporter_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'factory_address',
                'district',
                'state',
                'year_of_establishment',
                'textile_segment',
                'main_product_hsn',
                'installed_capacity',
                'major_machineries',
                'capacity_unit',
                'monthly_production',
                'annual_turnover',
                'is_exporter',
                'export_turnover',
                'major_exporting_countries',
                'product_images',
                'export_potential',
                'remarks'
            ]);
        });
    }
};
