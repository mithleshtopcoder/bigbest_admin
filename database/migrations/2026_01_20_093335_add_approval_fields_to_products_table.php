<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('status');

            $table->text('rejection_reason')
                  ->nullable()
                  ->after('approval_status');

            $table->timestamp('approved_at')
                  ->nullable()
                  ->after('rejection_reason');

            $table->unsignedBigInteger('approved_by')
                  ->nullable()
                  ->after('approved_at');

            // Optional FK (safe even if you skip)
            // $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // $table->dropForeign(['approved_by']);

            $table->dropColumn([
                'approval_status',
                'rejection_reason',
                'approved_at',
                'approved_by',
            ]);
        });
    }
};