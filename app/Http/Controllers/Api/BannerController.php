<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Get list of active banners
     * 
     * @param Request $request
     * @param string|null $type Optional banner type: home, category, product, promotional, other
     */
    public function index(Request $request, $type = null)
    {
        $allowedTypes = ['home', 'category', 'product', 'promotional', 'other'];
        
        // Validate type if provided
        if ($type && !in_array($type, $allowedTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid banner type. Allowed types: ' . implode(', ', $allowedTypes),
                'data' => []
            ], 422);
        }
        
        $query = Banner::where('is_active', true);
        
        if ($type) {
            $query->where('type', $type);
        }
        
        $banners = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($banner) {
                if ($banner->image) {
                    $banner->image = url('images/banner/' . $banner->image);
                }
                return $banner;
            });

        return response()->json([
            'success' => true,
            'data' => $banners
        ], 200);
    }
}
