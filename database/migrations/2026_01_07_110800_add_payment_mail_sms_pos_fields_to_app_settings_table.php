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
        Schema::table('app_settings', function (Blueprint $table) {
            // Payment
            $table->string('razorpay_key')->nullable()->after('free_delivery_threshold');
            $table->string('razorpay_secret')->nullable()->after('razorpay_key');
            $table->string('razorpay_webhook_secret')->nullable()->after('razorpay_secret');

            // Mail
            $table->string('mail_mailer')->nullable()->after('razorpay_webhook_secret');
            $table->string('mail_host')->nullable()->after('mail_mailer');
            $table->string('mail_port')->nullable()->after('mail_host');
            $table->string('mail_username')->nullable()->after('mail_port');
            $table->string('mail_password')->nullable()->after('mail_username');
            $table->string('mail_encryption')->nullable()->after('mail_password');
            $table->string('mail_from_address')->nullable()->after('mail_encryption');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
            $table->string('mail_bcc_address')->nullable()->after('mail_from_name');

            // SMS
            $table->string('sms_provider')->nullable()->after('mail_bcc_address');
            $table->string('sms_key')->nullable()->after('sms_provider');
            $table->string('sms_secret')->nullable()->after('sms_key');
            $table->string('sms_sender_id')->nullable()->after('sms_secret');
            $table->string('sms_template_id')->nullable()->after('sms_sender_id');

            // POS
            $table->boolean('pos_enabled')->default(0)->after('sms_template_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            // Payment
            $table->dropColumn(['razorpay_key', 'razorpay_secret', 'razorpay_webhook_secret']);

            // Mail
            $table->dropColumn([
                'mail_mailer', 'mail_host', 'mail_port', 'mail_username',
                'mail_password', 'mail_encryption', 'mail_from_address',
                'mail_from_name', 'mail_bcc_address'
            ]);

            // SMS
            $table->dropColumn(['sms_provider', 'sms_key', 'sms_secret', 'sms_sender_id', 'sms_template_id']);

            // POS
            $table->dropColumn('pos_enabled');
        });
    }
};