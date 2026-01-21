<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            
            // vendor_id links to users table but no foreign key
            $table->unsignedBigInteger('vendor_id');

            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payouts');
    }
};