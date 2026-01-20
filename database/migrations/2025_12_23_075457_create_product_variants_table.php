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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Variant Identification
            $table->string('name'); // e.g., "500g Packet", "1kg Loose", "Bunch of 5"
            $table->string('code')->nullable(); // Internal code for this variant
            $table->string('sku')->unique()->nullable(); // Stock Keeping Unit
            $table->string('barcode')->nullable(); // Barcode for billing/scanning
            
            $table->enum('unit', [
                'piece',     // Single piece/item
                'kg',        // Kilogram
                'gram',      // Gram
                'liter',     // Liter
                'ml',        // Milliliter
                'bundle',    // Bundle
                'bunch',     // Bunch (like bananas, coriander)
                'packet',    // Packet
                'box',       // Box
                'bottle',    // Bottle/jar
                'can',       // Can/tin
                'dozen',     // Dozen (12 pieces)
                'pack',      // Pack
                'loose'      // Loose/by weight
            ])->default('piece');
            $table->string('unit_value')->nullable(); // e.g., "500", "1kg", "Bunch", "Large"            
        
            // Status & Display
            $table->boolean('is_default')->default(false); // Default variant for product
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Feature this variant
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('product_id');
            $table->index('is_active');
            $table->index('is_default');
            $table->index('sku');
            $table->index('barcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
