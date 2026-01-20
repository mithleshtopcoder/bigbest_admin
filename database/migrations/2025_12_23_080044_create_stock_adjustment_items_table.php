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
        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_adjustment_id')->constrained('stock_adjustments')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('restrict');
            $table->integer('current_quantity'); // Quantity before adjustment
            $table->integer('adjusted_quantity'); // Positive for addition, negative for reduction
            $table->integer('new_quantity'); // Quantity after adjustment
            $table->decimal('unit_cost', 10, 2)->nullable(); // Cost per unit for value calculation
            $table->decimal('total_value', 12, 2)->default(0.00); // Total value of this adjustment
            $table->text('remark')->nullable(); // Remark for this specific adjustment item
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('stock_adjustment_id');
            $table->index('product_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_items');
    }
};
