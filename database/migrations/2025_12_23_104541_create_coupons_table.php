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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('coupon_type', ['coupon', 'discount'])->default('coupon');
            $table->enum('discount_type', ['percentage', 'fixed']); // percentage or fixed amount
            $table->decimal('discount_value', 10, 2); // Percentage or fixed amount
            $table->decimal('minimum_order_amount', 10, 2)->default(0.00);
            $table->decimal('maximum_discount_amount', 10, 2)->nullable(); // For percentage discounts
            $table->enum('applicable_to', ['all', 'category', 'sub_category', 'product', 'brand'])->default('all');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('cascade');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_user')->default(1); // Per customer limit
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_first_order_only')->default(false);
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('code');
            $table->index('is_active');
            $table->index(['valid_from', 'valid_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
