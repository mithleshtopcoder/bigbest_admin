<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Big Best',
                'company_title' => 'Fresh Organic Products - Farm to Door',
                'logo' => 'https://via.placeholder.com/400x100/4CAF50/FFFFFF?text=R+G+Organic+Mart',
                'small_logo' => 'https://via.placeholder.com/100x100/4CAF50/FFFFFF?text=RGOM',
                'url' => 'https://rgorganicmart.expensi.in',
                'email' => 'info@rgorganicmart.com',
                'phone' => '+91-9876543210',
                'address' => '123 Organic Street, Green Valley, Mumbai, Maharashtra 400001, India',
                'description' => 'Your trusted source for fresh, organic vegetables, fruits, and food products. We deliver farm-fresh produce directly to your doorstep.',
                'currency' => 'INR',
                'currency_symbol' => '₹',
                'timezone' => 'Asia/Kolkata',
                'language' => 'en',
                'facebook_url' => 'https://facebook.com/rgorganicmart',
                'twitter_url' => 'https://twitter.com/rgorganicmart',
                'instagram_url' => 'https://instagram.com/rgorganicmart',
                'youtube_url' => 'https://youtube.com/@rgorganicmart',
                'linkedin_url' => 'https://linkedin.com/company/rgorganicmart',
                'whatsapp_number' => '+919876543210',
                'about_us' => 'Big Best is committed to providing the freshest organic produce to our customers. We work directly with local farmers to bring you the best quality vegetables, fruits, and food products.',
                'min_order_amount' => 100.00,
                'delivery_charge' => 50.00,
                'free_delivery_threshold' => 500.00,
                'is_maintenance_mode' => false,
                'maintenance_message' => 'We are currently under maintenance. Please check back soon.',
            ]
        );
    }
}
