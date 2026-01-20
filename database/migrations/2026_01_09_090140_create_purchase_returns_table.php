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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('set null');
            $table->foreignId('purchase_receipt_id')->nullable()->constrained('purchase_receipts')->onDelete('set null');
            $table->foreignId('purchase_invoice_id')->nullable()->constrained('purchase_invoices')->onDelete('set null');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->foreignId('store_id')->constrained('stores')->onDelete('restrict');
            $table->enum('status', ['draft', 'pending', 'approved', 'processed', 'cancelled'])->default('draft');
            $table->enum('return_type', ['damaged', 'defective', 'wrong_item', 'excess', 'other'])->default('other');
            $table->date('return_date');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->enum('refund_status', ['pending', 'partially_refunded', 'refunded', 'credit_note_issued'])->default('pending');
            $table->decimal('refunded_amount', 12, 2)->default(0.00);
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->integer('total_items')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('supplier_id');
            $table->index('store_id');
            $table->index('status');
            $table->index('return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
