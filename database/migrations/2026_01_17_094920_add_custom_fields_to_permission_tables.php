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
        $tableNames = config('permission.table_names');
        
        // Add custom fields to roles table
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->boolean('is_system')->default(false)->after('description');
            $table->boolean('is_active')->default(true)->after('is_system');
        });

        // Add custom fields to permissions table
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->integer('sort_order')->default(0)->after('description');
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        
        // Remove custom fields from roles table
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'is_system', 'is_active']);
        });

        // Remove custom fields from permissions table
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'sort_order', 'is_active']);
        });
    }
};
