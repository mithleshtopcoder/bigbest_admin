<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {

            // make store_id nullable
            $table->unsignedBigInteger('store_id')->nullable()->change();

            // add variant_id (if not exists)
            $table->unsignedBigInteger('variant_id')->after('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable(false)->change();
            $table->dropColumn('variant_id');
        });
    }
};