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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('restrict');
            $table->foreignId('store_id')->constrained('stores')->onDelete('restrict');
            $table->enum('movement_type', [
                'purchase',           // Stock from purchase
                'sale',               // Stock sold
                'transfer_out',       // Stock transferred out
                'transfer_in',        // Stock transferred in
                'adjustment',         // Stock adjustment
                'return',             // Customer return
                'damage',             // Damaged stock
                'expiry',             // Expired stock
                'reserved',           // Reserved for order
                'unreserved'          // Unreserved from order
            ]);
            $table->integer('quantity'); // Positive for in, negative for out
            $table->integer('balance_after'); // Stock balance after this movement
            $table->string('reference_type')->nullable(); // purchase_order, sale_order, stock_transfer, stock_adjustment, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the reference
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('movement_date');
            $table->timestamps();
            
            $table->index('product_variant_id');
            $table->index('store_id');
            $table->index('movement_type');
            $table->index('movement_date');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
