<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Get list of active brands
     */
   public function index()
{
    $brands = Brand::where('is_active', 1)
        ->select('id', 'name', 'slug','logo')
        ->orderBy('sort_order')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $brands
    ]);
}


    /**
     * View a single brand
     */
    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $brand
        ], 200);
    }
}