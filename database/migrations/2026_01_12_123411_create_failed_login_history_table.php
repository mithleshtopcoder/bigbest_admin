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
        Schema::create('failed_login_history', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device')->nullable(); // Browser/Device info
            $table->string('platform')->nullable(); // OS info
            $table->string('reason')->nullable(); // Invalid password, user not found, account locked, etc.
            $table->timestamp('attempted_at');
            $table->timestamps();
            
            $table->index(['email', 'attempted_at']);
            $table->index('ip_address');
            $table->index('attempted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_login_history');
    }
};
