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
        Schema::create('combo_offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('original_price', 10, 2); // Total price without discount
            $table->decimal('discounted_price', 10, 2); // Combo price
            $table->decimal('discount_amount', 10, 2); // Calculated: original_price - discounted_price
            $table->decimal('discount_percentage', 5, 2); // Calculated discount percentage
            $table->integer('min_quantity')->default(1);
            $table->integer('max_quantity')->nullable();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->time('valid_from_time')->nullable();
            $table->time('valid_to_time')->nullable();
            $table->integer('stock_quantity')->nullable(); // Limited stock combos
            $table->integer('sold_quantity')->default(0);
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index(['valid_from', 'valid_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_offers');
    }
};
