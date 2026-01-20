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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tax Name (e.g., "GST 5%")
            $table->enum('type', ['GST', 'VAT', 'CST', 'IGST', 'CGST', 'SGST', 'Other'])->default('GST');
            $table->decimal('rate', 5, 2)->default(0.00); // Overall tax rate (%)
            $table->decimal('cgst_rate', 5, 2)->nullable(); // CGST rate (%)
            $table->decimal('sgst_rate', 5, 2)->nullable(); // SGST rate (%)
            $table->decimal('igst_rate', 5, 2)->nullable(); // IGST rate (%)
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
