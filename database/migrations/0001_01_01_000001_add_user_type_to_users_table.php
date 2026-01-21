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
            // Add user_type if it doesn't exist
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['admin', 'customer'])->default('customer')->after('id');
            }

            // Add is_access if it doesn't exist
            if (!Schema::hasColumn('users', 'is_access')) {
                $table->enum('is_access', ['admin', 'user'])->default('user')->after('user_type');
            }

            // Add vendor_id if it doesn't exist
            if (!Schema::hasColumn('users', 'vendor_id')) {
                $table->string('vendor_id')->unique()->nullable()->after('is_access');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'vendor_id')) {
                $table->dropColumn('vendor_id');
            }

            if (Schema::hasColumn('users', 'is_access')) {
                $table->dropColumn('is_access');
            }

            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type');
            }
        });
    }
};