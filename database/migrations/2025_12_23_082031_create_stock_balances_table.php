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
        Schema::create('stock_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->integer('available_quantity')->default(0); // Available for sale
            $table->integer('reserved_quantity')->default(0); // Reserved for orders
            $table->integer('in_transit_quantity')->default(0); // In transit from other stores
            $table->integer('pending_quantity')->default(0); // Pending from purchases
            $table->integer('total_quantity')->default(0); // Calculated: available + reserved + in_transit + pending
            $table->date('last_movement_date')->nullable();
            $table->timestamps();
            
            $table->unique(['product_variant_id', 'store_id']);
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_balances');
    }
};
