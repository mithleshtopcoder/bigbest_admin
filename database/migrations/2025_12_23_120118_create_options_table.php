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
        Schema::create('options', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('value');

            $table->string('display_image')->nullable();

            $table->unsignedBigInteger('option_master_id');

            $table->boolean('default')->default(false);
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Indexes
            $table->index('option_master_id');
            $table->index('status');

            // Foreign key (if option_masters table exists)
            $table->foreign('option_master_id')
                  ->references('id')
                  ->on('option_masters')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};