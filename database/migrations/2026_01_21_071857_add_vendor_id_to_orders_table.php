<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('vendor_id')->nullable()->after('store_id'); // Using string since vendor_id in users table is like 'VND-20260121062314-697070d24e76c'
            $table->index('vendor_id'); // optional, for faster queries
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('vendor_id');
        });
    }
};