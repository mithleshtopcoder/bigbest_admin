<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();

            // Vendor relation
            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->cascadeOnDelete();

            // Payout details
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable(); // bank, upi, cash, etc
            $table->string('transaction_id')->nullable();

            // Status
            $table->enum('status', ['pending', 'paid'])->default('pending');

            // Optional admin note
            $table->text('remark')->nullable();

            // Who created / approved
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payouts');
    }
};