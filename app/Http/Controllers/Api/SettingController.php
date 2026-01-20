<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Helpers\MyHelper;

class SettingController extends Controller
{
    /**
     * Get all app settings
     */
   public function index()
{
    try {
        $settings = AppSetting::getSettings();

        // Map setting key → folder
        $imageFolders = [
            'logo'    => 'images/logo/',
            'small_logo' => 'images/icons/',
        ];

        foreach ($imageFolders as $key => $folder) {
            if (!empty($settings[$key])) {
                $settings[$key] = url($folder . $settings[$key]);
            } else {
                $settings[$key] = null;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings retrieved successfully',
            'data' => $settings,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve settings',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Get specific setting by key
     */
    public function show($key)
    {
        try {
            $settings = AppSetting::getSettings();
            
            if (!$settings->exists || !isset($settings->$key)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Setting retrieved successfully',
                'data' => [
                    'key' => $key,
                    'value' => $settings->$key,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve setting',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get company information (logo, name, etc.)
     */
    public function companyInfo()
    {
        try {
            $settings = AppSetting::getSettings();

            return response()->json([
                'success' => true,
                'message' => 'Company information retrieved successfully',
                'data' => [
                    'company_name' => $settings->company_name,
                    'company_title' => $settings->company_title,
                    'logo' => MyHelper::getImage($settings->logo,'settings'),
                    'small_logo' => MyHelper::getImage($settings->small_logo,'settings'),
                    'url' => $settings->url,
                    'email' => $settings->email,
                    'phone' => $settings->phone,
                    'address' => $settings->address,
                    'description' => $settings->description,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve company information',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get social media links
     */
    public function socialMedia()
    {
        try {
            $settings = AppSetting::getSettings();

            return response()->json([
                'success' => true,
                'message' => 'Social media links retrieved successfully',
                'data' => [
                    'facebook_url' => $settings->facebook_url,
                    'twitter_url' => $settings->twitter_url,
                    'instagram_url' => $settings->instagram_url,
                    'youtube_url' => $settings->youtube_url,
                    'linkedin_url' => $settings->linkedin_url,
                    'whatsapp_number' => $settings->whatsapp_number,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve social media links',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get delivery settings
     */
    public function deliverySettings()
    {
        try {
            $settings = AppSetting::getSettings();

            return response()->json([
                'success' => true,
                'message' => 'Delivery settings retrieved successfully',
                'data' => [
                    'min_order_amount' => $settings->min_order_amount,
                    'delivery_charge' => $settings->delivery_charge,
                    'free_delivery_threshold' => $settings->free_delivery_threshold,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve delivery settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check maintenance mode
     */
    public function maintenanceMode()
    {
        try {
            $settings = AppSetting::getSettings();

            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode status retrieved successfully',
                'data' => [
                    'is_maintenance_mode' => $settings->is_maintenance_mode ?? false,
                    'maintenance_message' => $settings->maintenance_message,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance mode status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}