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
        Schema::create('store_service_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->string('area_name'); // e.g., "Downtown", "North Zone"
            $table->string('pincode')->nullable(); // Specific pincode
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('radius_km')->default(10); // Service radius in kilometers
            $table->decimal('min_order_amount', 10, 2)->default(0.00); // Minimum order for delivery
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('store_id');
            $table->index('pincode');
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_service_areas');
    }
};
