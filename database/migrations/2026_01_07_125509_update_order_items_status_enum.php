<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('pending','confirmed','preparing','ready','shipped','delivered','cancelled','returned','hold') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('pending','confirmed','preparing','ready','shipped','delivered','cancelled','returned') NOT NULL DEFAULT 'pending'");
    }
};