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
        Schema::create('employee_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('employee_code')->unique()->nullable();
            $table->foreignId('employee_type_id')->nullable()->constrained('options')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('designation_id')->nullable()->constrained('designations')->onDelete('set null');
            $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('set null');
            $table->date('joining_date')->nullable();
            $table->foreignId('report_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('resign_date')->nullable();
            $table->boolean('resign_status')->default(false);
            $table->string('expresnce')->nullable();
            $table->date('date_brith')->nullable();
            $table->foreignId('marital_status_id')->nullable()->constrained('options')->onDelete('set null');
            $table->foreignId('blood_group_id')->nullable()->constrained('options')->onDelete('set null');
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('emergency_contact_relation_name')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('employee_code');
            $table->index('department_id');
            $table->index('designation_id');
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_profile');
    }
};
