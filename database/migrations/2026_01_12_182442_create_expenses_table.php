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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('expense_number')->unique();
            $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('options')->onDelete('set null'); // Expense Category from options
            $table->foreignId('payment_method_id')->nullable()->constrained('options')->onDelete('set null'); // Payment Method from options
            $table->foreignId('status_id')->nullable()->constrained('options')->onDelete('set null'); // Status from options
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('expense_number');
            $table->index('store_id');
            $table->index('expense_date');
            $table->index('category_id');
            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
