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
         $table->enum('user_type', ['user', 'admin'])->default('user');
         $table->enum('is_access', ['admin', 'vendor'])->default('user');
    });
}

public function down()
{
    Schema::table('users', function ($table) {
        $table->dropColumn('user_type');
        $table->dropColumn('is_access');
    });
}

};