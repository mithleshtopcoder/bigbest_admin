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
        Schema::create('joining_checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->string('task_category')->nullable(); // hr, it, admin, finance, etc.
            $table->integer('sort_order')->default(0);
            $table->boolean('is_mandatory')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('joining_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onboarding_process_id')->constrained('onboarding_processes')->onDelete('cascade');
            $table->foreignId('checklist_template_id')->nullable()->constrained('joining_checklist_templates')->onDelete('set null');
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->string('task_category')->nullable();
            $table->enum('status', ['pending', 'in-progress', 'completed', 'skipped'])->default('pending');
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->text('completion_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joining_checklists');
        Schema::dropIfExists('joining_checklist_templates');
    }
};
