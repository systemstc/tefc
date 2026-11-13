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
        // 1. Master Categories (CAT-01 to CAT-06)
        Schema::create('textile_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // CAT-01, CAT-02...
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Subcategories
        Schema::create('textile_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('textile_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'is_active']);
            $table->unique(['category_id', 'slug']);
        });

        // 3. Products in Hierarchy (Classification items)
        Schema::create('textile_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained('textile_subcategories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('default_hs_code')->nullable();
            $table->text('synonyms')->nullable(); // Search keywords
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['subcategory_id', 'is_active']);
            $table->index('name');
        });

        // 4. Category Attribute Rules (Contextual visibility)
        Schema::create('category_attribute_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('textile_categories')->onDelete('cascade');
            $table->string('attribute_type', 50); // fibre, yarn, fabric, process
            $table->enum('requirement_level', ['required', 'relevant', 'optional', 'hidden'])->default('relevant');
            $table->boolean('is_multiple')->default(true);
            $table->string('help_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['category_id', 'attribute_type']);
        });

        // 5. Technical Attribute Masters: Fibres
        Schema::create('fibre_masters', function (Blueprint $table) {
            $table->id();
            $table->string('group_name'); // Natural Plant, Synthetic, etc.
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group_name', 'is_active']);
        });

        // 6. Technical Attribute Masters: Yarns
        Schema::create('yarn_masters', function (Blueprint $table) {
            $table->id();
            $table->string('group_name'); // Spun, Filament, etc.
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group_name', 'is_active']);
        });

        // 7. Technical Attribute Masters: Fabrics
        Schema::create('fabric_masters', function (Blueprint $table) {
            $table->id();
            $table->string('group_name'); // Woven, Knitted, etc.
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group_name', 'is_active']);
        });

        // 8. Technical Attribute Masters: Processes
        Schema::create('process_masters', function (Blueprint $table) {
            $table->id();
            $table->string('group_name'); // Construction, Dyeing, Printing, etc.
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group_name', 'is_active']);
        });

        // 9. Extend products table with classification foreign keys & new metadata
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('textile_category_id')->nullable()->after('category')->constrained('textile_categories')->nullOnDelete();
            $table->foreignId('textile_subcategory_id')->nullable()->after('textile_category_id')->constrained('textile_subcategories')->nullOnDelete();
            $table->foreignId('textile_product_id')->nullable()->after('textile_subcategory_id')->constrained('textile_products')->nullOnDelete();
            $table->string('moq')->nullable()->after('width_size');
            $table->string('unit_of_measurement')->nullable()->after('moq');
            $table->text('tags')->nullable()->after('unit_of_measurement');
            $table->boolean('is_published')->default(true)->after('status');
        });

        // 10. Product Attribute Values
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('attribute_type', 50); // fibre, yarn, fabric, process, technical
            $table->unsignedBigInteger('master_id')->nullable();
            $table->string('attribute_name'); // E.g. "Cotton", "Ring-Spun"
            $table->string('attribute_value')->nullable(); // E.g. "100%", "40s Ne", "150 GSM"
            $table->decimal('percentage', 5, 2)->nullable(); // E.g. 100.00, 80.00
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'attribute_type']);
        });

        // 11. Extend product_media with primary flag and ordering
        Schema::table('product_media', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('media_type');
            $table->integer('sort_order')->default(0)->after('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_media', function (Blueprint $table) {
            $table->dropColumn(['is_primary', 'sort_order']);
        });

        Schema::dropIfExists('product_attribute_values');

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['textile_category_id']);
            $table->dropForeign(['textile_subcategory_id']);
            $table->dropForeign(['textile_product_id']);
            $table->dropColumn([
                'textile_category_id',
                'textile_subcategory_id',
                'textile_product_id',
                'moq',
                'unit_of_measurement',
                'tags',
                'is_published'
            ]);
        });

        Schema::dropIfExists('process_masters');
        Schema::dropIfExists('fabric_masters');
        Schema::dropIfExists('yarn_masters');
        Schema::dropIfExists('fibre_masters');
        Schema::dropIfExists('category_attribute_rules');
        Schema::dropIfExists('textile_products');
        Schema::dropIfExists('textile_subcategories');
        Schema::dropIfExists('textile_categories');
    }
};
