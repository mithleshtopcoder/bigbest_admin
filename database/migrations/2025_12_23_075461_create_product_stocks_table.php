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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(0); // Available stock in store
            $table->integer('reserved_quantity')->default(0); // Reserved for pending orders
            $table->integer('in_transit_quantity')->default(0); // Stock being transferred from other stores
            $table->integer('pending_quantity')->default(0); // Stock pending from purchase orders
            $table->integer('min_stock_level')->default(0); // Reorder point
            $table->integer('max_stock_level')->nullable();
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock', 'backorder'])->default('out_of_stock');
            $table->date('last_restocked_at')->nullable();
            $table->foreignId('last_restocked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['product_variant_id', 'store_id']);
            $table->index('store_id');
            $table->index('stock_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
