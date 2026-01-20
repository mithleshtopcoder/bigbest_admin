<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    // First, ensure 'cash' is in the ENUM so we can update the data
    DB::statement("
        ALTER TABLE orders 
        MODIFY payment_method ENUM(
            'cash_on_delivery',
            'cash',
            'online',
            'wallet',
            'card',
            'upi',
            'netbanking'
        ) NULL
    ");
    
    // Then, update all existing 'cash_on_delivery' values to 'cash'
    DB::table('orders')
        ->where('payment_method', 'cash_on_delivery')
        ->update(['payment_method' => 'cash']);
    
    // Finally, remove 'cash_on_delivery' from the ENUM
    DB::statement("
        ALTER TABLE orders 
        MODIFY payment_method ENUM(
            'cash',
            'online',
            'wallet',
            'card',
            'upi',
            'netbanking'
        ) NULL
    ");
}

public function down()
{
    // First, add 'cash_on_delivery' back to the ENUM
    DB::statement("
        ALTER TABLE orders 
        MODIFY payment_method ENUM(
            'cash_on_delivery',
            'cash',
            'online',
            'wallet',
            'card',
            'upi',
            'netbanking'
        ) NULL
    ");
    
    // Then, update all 'cash' values back to 'cash_on_delivery'
    DB::table('orders')
        ->where('payment_method', 'cash')
        ->update(['payment_method' => 'cash_on_delivery']);
    
    // Finally, remove 'cash' from the ENUM
    DB::statement("
        ALTER TABLE orders 
        MODIFY payment_method ENUM(
            'cash_on_delivery',
            'online',
            'wallet',
            'card',
            'upi',
            'netbanking'
        ) NULL
    ");
}

};