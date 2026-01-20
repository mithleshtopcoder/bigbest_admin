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
        Schema::create('support_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
            $table->enum('replied_by_type', ['customer', 'admin'])->default('customer');
            $table->foreignId('replied_by_id')->nullable(); // Can be customer_id or user_id
            $table->text('message');
            $table->json('attachments')->nullable(); // Array of file paths
            $table->boolean('is_internal')->default(false); // Internal notes visible only to admins
            $table->timestamps();
            
            $table->index(['ticket_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_replies');
    }
};
