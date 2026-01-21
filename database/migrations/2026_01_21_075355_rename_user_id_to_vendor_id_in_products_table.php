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
        Schema::table('products', function (Blueprint $table) {
            // 1️⃣ Drop the old user_id column
            $table->dropForeign(['user_id']); // drop foreign key first if exists
            $table->dropColumn('user_id');

            // 2️⃣ Add new vendor_id column
            $table->unsignedBigInteger('vendor_id')->after('id');
            $table->foreign('vendor_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');

            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->after('id');
        });
    }
};