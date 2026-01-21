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
        Schema::create('vendor_documents', function (Blueprint $table) {
    $table->id();
    $table->string('vendor_id')->index();
    $table->string('pan_number')->nullable();
    $table->string('aadhar_number')->nullable();
    $table->string('gst_number')->nullable();

    $table->string('pan_file')->nullable();
    $table->string('aadhar_file')->nullable();
    $table->string('gst_certificate')->nullable();

    $table->text('address')->nullable();

    // Bank details
    $table->string('bank_name')->nullable();
    $table->string('account_number')->nullable();
    $table->string('account_type')->nullable();
    $table->string('ifsc_code')->nullable();
    $table->string('branch_name')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_documents');
    }
};