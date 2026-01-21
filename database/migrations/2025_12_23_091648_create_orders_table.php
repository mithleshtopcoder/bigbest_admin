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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('store_id')->constrained('stores')->onDelete('restrict');
            $table->foreignId('delivery_address_id')->nullable()->constrained('customer_addresses')->onDelete('set null');
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'ready_to_ship',
                'shipped',
                'out_for_delivery',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->enum('payment_method', ['cash_on_delivery', 'online', 'wallet', 'card', 'upi', 'netbanking'])->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('shipping_charge', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('loyalty_points_used', 10, 2)->default(0.00);
            $table->decimal('wallet_amount_used', 10, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->text('delivery_instructions')->nullable();
            $table->date('delivery_date')->nullable();
            $table->time('delivery_time_slot')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->integer('total_items')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('customer_id');
            $table->index('store_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
