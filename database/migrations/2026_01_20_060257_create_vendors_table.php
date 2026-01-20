<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password'); // hashed password
            $table->string('store_name')->nullable();
            $table->text('address')->nullable();

            // KYC Documents
            $table->string('pan_number')->nullable();
            $table->string('pan_file')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('gst_file')->nullable();
            $table->string('address_proof_type')->nullable();
            $table->string('address_proof_file')->nullable();

            // Vendor Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};