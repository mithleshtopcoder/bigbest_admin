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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('offer_type', ['product', 'category', 'sub_category', 'brand', 'cart']); // cart = cart total discount
            $table->enum('discount_type', ['percentage', 'fixed', 'buy_x_get_y']); // buy_x_get_y for special offers
            $table->decimal('discount_value', 10, 2)->nullable(); // For percentage or fixed
            $table->integer('buy_quantity')->nullable(); // For buy_x_get_y
            $table->integer('get_quantity')->nullable(); // For buy_x_get_y
            $table->decimal('minimum_order_amount', 10, 2)->default(0.00);
            $table->decimal('maximum_discount_amount', 10, 2)->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('cascade');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->time('valid_from_time')->nullable();
            $table->time('valid_to_time')->nullable();
            $table->integer('priority')->default(0); // Higher priority offers applied first
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
        Schema::dropIfExists('offers');
    }
};
