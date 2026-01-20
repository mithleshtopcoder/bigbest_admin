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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_source', ['online', 'pos'])->default('online')->after('delivery_address_id');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('order_source');
            $table->index('order_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_source']);
            $table->dropForeign(['created_by']);
            $table->dropColumn(['order_source', 'created_by']);
        });
    }
};
