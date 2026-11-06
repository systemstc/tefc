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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            $table->string('designation')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('status', 50)->default('Pending')->change();
            $table->foreignId('created_by_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->timestamp('acknowledged_at')->nullable()->after('status');
            $table->foreignId('acknowledged_by')->nullable()->after('acknowledged_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['created_by_user_id']);
            $table->dropForeign(['acknowledged_by']);
            $table->dropColumn(['created_by_user_id', 'acknowledged_at', 'acknowledged_by']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'designation', 'phone']);
        });
    }
};
