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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('job_opening_id')->nullable()->constrained('job_openings')->onDelete('set null');
            $table->string('interview_type')->nullable(); // phone, video, in-person
            $table->dateTime('scheduled_at');
            $table->dateTime('completed_at')->nullable();
            $table->string('location')->nullable();
            $table->text('meeting_link')->nullable();
            $table->foreignId('interviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('interview_notes')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5
            $table->enum('status', ['scheduled', 'in-progress', 'completed', 'cancelled', 'rescheduled', 'no-show'])->default('scheduled');
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
