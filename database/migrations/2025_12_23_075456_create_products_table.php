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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // bigint unsigned
           $table->string('vendor_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->restrictOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->nullOnDelete();
            
            $table->string('barcode')->nullable();
            $table->integer('item_type')->nullable();
            $table->string('item_code')->nullable();
            $table->integer('collection')->nullable();
            $table->integer('season')->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->enum('packaging_type', [
                'loose',       // Sold loose/by weight
                'packet',      // Pre-packaged
                'bundle',      // Bundled items
                'bunch',       // Bunch (like bananas, coriander)
                'box',         // Box packaging
                'bottle',      // Bottle/jar
                'can',         // Can/tin
                'custom'       // Custom packaging
            ])->nullable();

            $table->integer('shelf_life_days')->nullable();
            $table->string('origin_country')->nullable();
            
            $table->text('ingredients')->nullable();
            $table->text('nutritional_info')->nullable();
            $table->string('storage_location')->nullable();
            $table->text('storage_instructions')->nullable();

            $table->string('thumbnail_image')->nullable();

            $table->integer('min_order_quantity')->default(1);
            $table->integer('max_order_quantity')->nullable();

            $table->enum('status', ['active','inactive','out_of_stock','discontinued'])->default('active');
            $table->enum('variant_type', ['single', 'multiple'])->default('single');

            $table->boolean('is_featured')->default(false);
            $table->boolean('top_selling_product')->default(false);
            $table->integer('sort_order')->default(0);
            $table->integer('view_count')->default(0);

            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('sub_category_id');
            $table->index('brand_id');
            $table->index('tax_id');
            $table->index('status');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};