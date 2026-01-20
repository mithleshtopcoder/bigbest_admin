<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders 
            MODIFY payment_method 
            ENUM(
                'cash_on_delivery',
                'cash',
                'online',
                'wallet',
                'card',
                'upi',
                'netbanking'
            ) NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE orders 
            MODIFY payment_method 
            ENUM(
            'cash',
                'online',
                'wallet',
                'card',
                'upi',
                'netbanking'
            ) NULL
        ");
    }
};