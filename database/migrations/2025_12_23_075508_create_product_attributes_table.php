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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('attribute_name'); // e.g., "Organic Certified", "Gluten Free", "Expiry Date"
            $table->string('attribute_value')->nullable(); // e.g., "Yes", "USDA Organic", "2024-12-31"
            $table->string('attribute_type')->nullable(); // text, boolean, date, number
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
