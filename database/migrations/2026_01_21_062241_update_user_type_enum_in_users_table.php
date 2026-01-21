<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Replace 'customer' with 'vendor' in ENUM
        DB::statement("ALTER TABLE users MODIFY user_type ENUM('admin','vendor') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ENUM back to original
        DB::statement("ALTER TABLE users MODIFY user_type ENUM('admin','customer') NOT NULL");
    }
};