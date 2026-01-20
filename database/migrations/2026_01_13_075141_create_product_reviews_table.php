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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->id();
            
            // Foreign keys
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');
            
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');
            
            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->onDelete('set null');
            
            // Review data
            $table->integer('rating')->default(1); // 1-5
            $table->text('review')->nullable();
            
            // Status for moderation
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Verified purchase flag
            $table->boolean('is_verified_purchase')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('product_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('rating');
            
            // Prevent duplicate reviews from same customer for same product
            // Note: Soft deletes are handled in application logic
            $table->unique(['product_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
