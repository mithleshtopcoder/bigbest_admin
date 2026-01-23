<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_payouts', function (Blueprint $table) {
            // Change vendor_id to string
            $table->string('vendor_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_payouts', function (Blueprint $table) {
            // Revert back to integer if rollback
            $table->unsignedBigInteger('vendor_id')->change();
        });
    }
};