<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communication_providers', function (Blueprint $table) {
            $table->id();
            $table->string('channel'); // sms | whatsapp | email | etc
            $table->string('provider_name')->nullable();
            $table->string('base_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('sender_id')->nullable();
            $table->string('route')->nullable();
            $table->string('default_template_id')->nullable();
            $table->json('meta')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Ensure only one active row per channel (mysql allows multiple NULLs but not duplicate same values)
            $table->unique(['channel', 'is_active'], 'communication_providers_channel_active_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_providers');
    }
};