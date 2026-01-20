<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactFieldsToEmployeeProfileTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_profile', function (Blueprint $table) {
            $table->string('address_line_1')->nullable()->after('expresnce');
            $table->string('address_line_2')->nullable()->after('address_line_1');
            $table->string('country')->nullable()->after('address_line_2');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('pincode', 6)->nullable()->after('city');
            $table->string('mobile_number', 15)->nullable()->after('pincode');
            $table->string('phone', 15)->nullable()->after('mobile_number');
            $table->string('user_name')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_profile', function (Blueprint $table) {
            $table->dropColumn([
                'address_line_1',
                'address_line_2',
                'country',
                'state',
                'city',
                'pincode',
                'mobile_number',
                'phone',
                'user_name',
            ]);
        });
    }
}