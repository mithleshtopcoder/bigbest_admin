<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_banks', function (Blueprint $table) {
            $table->id();

            // Link to vendor
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');

            // Bank details
            $table->string('account_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('bank_name');
            $table->string('bank_file')->nullable(); // canceled cheque or statement

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_banks');
    }
};