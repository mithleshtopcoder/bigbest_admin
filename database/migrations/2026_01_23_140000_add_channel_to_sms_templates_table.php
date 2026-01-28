<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sms_templates', function (Blueprint $table) {
            $table->string('channel')->default('sms')->after('id');
        });

        Schema::table('sms_templates', function (Blueprint $table) {
            // drop old unique on key (created by $table->string('key')->unique())
            $table->dropUnique('sms_templates_key_unique');
            $table->unique(['channel', 'key'], 'sms_templates_channel_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('sms_templates', function (Blueprint $table) {
            $table->dropUnique('sms_templates_channel_key_unique');
            $table->unique('key', 'sms_templates_key_unique');
            $table->dropColumn('channel');
        });
    }
};