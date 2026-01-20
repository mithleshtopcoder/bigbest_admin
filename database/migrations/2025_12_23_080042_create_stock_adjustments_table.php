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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number')->unique();
            $table->foreignId('store_id')->constrained('stores')->onDelete('restrict');
            $table->enum('type', ['addition', 'reduction', 'correction']); // addition = stock increase, reduction = stock decrease, correction = both
            $table->enum('reason', [
                'damaged', 
                'expired', 
                'lost', 
                'stolen', 
                'found', 
                'returned', 
                'count_error', 
                'physical_count',
                'vendor_return', 
                'promotion', 
                'opening_stock',
                'other'
            ])->default('other');
            $table->text('reason_description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->date('adjustment_date');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->integer('total_items')->default(0);
            $table->decimal('total_value', 12, 2)->default(0.00); // Total value of adjusted stock
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('store_id');
            $table->index('type');
            $table->index('status');
            $table->index('adjustment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
