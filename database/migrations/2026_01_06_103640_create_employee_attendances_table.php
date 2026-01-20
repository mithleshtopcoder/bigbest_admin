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
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_profile_id')->constrained('employee_profile')->onDelete('cascade');
            $table->date('attendance_date');
            $table->time('punch_in')->nullable();
            $table->time('punch_out')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->enum('status', ['present', 'absent', 'half_day', 'leave', 'holiday', 'weekend'])->default('absent');
            $table->text('remarks')->nullable();
            $table->enum('attendance_type', ['auto', 'manual'])->default('auto');
            $table->foreignId('marked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['employee_profile_id', 'attendance_date']);
            $table->index('attendance_date');
            $table->index('status');
            $table->index('attendance_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};
