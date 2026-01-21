<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('users', function ($table) {
         $table->enum('user_type', ['admin', 'customer'])->default('customer');
         $table->enum('is_access', ['admin', 'user'])->default('user');
         $table->string('vendor_id')->unique()->nullable();
    });
}

public function down()
{
    Schema::table('users', function ($table) {
        $table->dropColumn('user_type');
        $table->dropColumn('is_access');
        $table->dropColumn('vendor_id');
    });
}

};