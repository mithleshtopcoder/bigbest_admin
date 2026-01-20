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
        $table->string('user_type')->default('customer')->after('status')->comment('Type of user: admin, vendor, customer');
    });
}

public function down()
{
    Schema::table('users', function ($table) {
        $table->dropColumn('user_type');
    });
}

};