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
        Schema::create('employee_salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_profile_id')->constrained('employee_profile')->onDelete('cascade');
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('hra', 10, 2)->nullable()->default(0);
            $table->decimal('da', 10, 2)->nullable()->default(0);
            $table->decimal('ta', 10, 2)->nullable()->default(0);
            $table->decimal('medical_allowance', 10, 2)->nullable()->default(0);
            $table->decimal('other_allowances', 10, 2)->nullable()->default(0);
            $table->decimal('pf', 10, 2)->nullable()->default(0);
            $table->decimal('esi', 10, 2)->nullable()->default(0);
            $table->decimal('tds', 10, 2)->nullable()->default(0);
            $table->decimal('other_deductions', 10, 2)->nullable()->default(0);
            $table->decimal('gross_salary', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->date('effective_date')->nullable();
            $table->string('currency', 10)->default('INR');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('employee_profile_id');
            $table->index('effective_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_structures');
    }
};
