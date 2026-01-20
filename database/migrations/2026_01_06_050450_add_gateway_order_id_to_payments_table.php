<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('payments', 'gateway_order_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('gateway_order_id')
                      ->nullable()
                      ->after('payment_gateway')
                      ->index();
            });
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('gateway_order_id');
        });
    }
};