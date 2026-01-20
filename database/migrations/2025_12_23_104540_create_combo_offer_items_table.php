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
        Schema::create('combo_offer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_offer_id')->constrained('combo_offers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2); // Price at time of combo creation
            $table->decimal('total_price', 10, 2); // unit_price * quantity
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('combo_offer_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_offer_items');
    }
};
