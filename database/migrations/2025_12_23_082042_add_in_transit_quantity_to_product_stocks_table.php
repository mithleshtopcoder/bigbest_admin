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
        Schema::table('product_stocks', function (Blueprint $table) {
            if (!Schema::hasColumn('product_stocks', 'in_transit_quantity')) {
                $table->integer('in_transit_quantity')->default(0)->after('reserved_quantity');
            }
            if (!Schema::hasColumn('product_stocks', 'pending_quantity')) {
                $table->integer('pending_quantity')->default(0)->after('in_transit_quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            if (Schema::hasColumn('product_stocks', 'in_transit_quantity')) {
                $table->dropColumn('in_transit_quantity');
            }
            if (Schema::hasColumn('product_stocks', 'pending_quantity')) {
                $table->dropColumn('pending_quantity');
            }
        });
    }
};
