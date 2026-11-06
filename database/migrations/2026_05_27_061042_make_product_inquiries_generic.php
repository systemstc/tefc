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
        Schema::table('product_inquiries', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_inquiries', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->string('quantity_required')->nullable();
            $table->string('category')->nullable();
            $table->string('material')->nullable();
            $table->string('pattern')->nullable();
            $table->string('width_size')->nullable();
            $table->text('additional_requirements')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_inquiries', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_inquiries', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->dropColumn([
                'quantity_required',
                'category',
                'material',
                'pattern',
                'width_size',
                'additional_requirements'
            ]);
        });
    }
};
