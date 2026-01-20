<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'company_name',
        'company_title',
        'logo',
        'small_logo',
        'url',
        'email',
        'phone',
        'address',
        'header',
        'template',
        'description',
        'currency',
        'currency_symbol',
        'timezone',
        'language',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'whatsapp_number',
        'about_us',
        'min_order_amount',
        'delivery_charge',
        'delivery_tax_percent',
        'free_delivery_threshold',
        // Payment
    'razorpay_key',
    'razorpay_secret',
    'razorpay_webhook_secret',

    // Mail
    'mail_mailer',
    'mail_host',
    'mail_port',
    'mail_username',
    'mail_password',
    'mail_encryption',
    'mail_from_address',
    'mail_from_name',
    'mail_bcc_address',

    // SMS
    'sms_provider',
    'sms_key',
    'sms_secret',
    'sms_sender_id',
    'sms_template_id',

    // POS
    'pos_enabled',
        'is_maintenance_mode',
        'maintenance_message',
    ];

    protected function casts(): array
    {
        return [
            'min_order_amount' => 'decimal:2',
            'delivery_charge' => 'decimal:2',
            'free_delivery_threshold' => 'decimal:2',
            'is_maintenance_mode' => 'boolean',
        ];
    }

    protected $image_folder = 'settings';

public function getImageUrlAttribute()
{
    if ($this->image) {
        return \App\Helpers\MyHelper::getImage($this->image, $this->image_folder);
    }
    return null;
}
    /**
     * Get settings - returns first record or creates default
     */
    public static function getSettings()
    {
        $settings = self::first();
        if (!$settings) {
            // Create default settings if none exist
            $settings = self::create([
                'company_name' => 'R G Organic Mart',
                'company_title' => 'Fresh Organic Products',
                'currency' => 'INR',
                'currency_symbol' => '₹',
                'timezone' => 'Asia/Kolkata',
                'language' => 'en',
            ]);
        }
        return $settings;
    }
}