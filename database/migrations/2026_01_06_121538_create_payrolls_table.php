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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_profile_id')->constrained('employee_profile')->onDelete('cascade');
            $table->foreignId('salary_structure_id')->nullable()->constrained('employee_salary_structures')->onDelete('set null');
            $table->string('payroll_period'); // e.g., "2026-01" for January 2026
            $table->date('period_start_date');
            $table->date('period_end_date');
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('leave_days')->default(0);
            $table->integer('holiday_days')->default(0);
            $table->integer('weekend_days')->default(0);
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->decimal('total_allowances', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('attendance_bonus', 10, 2)->default(0);
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('advance_deduction', 10, 2)->default(0);
            $table->decimal('other_adjustments', 10, 2)->default(0);
            $table->enum('status', ['draft', 'processed', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->date('payment_date')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('employee_profile_id');
            $table->index('payroll_period');
            $table->index('status');
            $table->index(['period_start_date', 'period_end_date']);
            $table->unique(['employee_profile_id', 'payroll_period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
