<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Get all policies
     */
    public function index()
    {
        try {
            $policies = Policy::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->groupBy('type');

            return response()->json([
                'success' => true,
                'message' => 'Policies retrieved successfully',
                'data' => $policies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve policies',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get policy by type
     */
    public function show($type)
    {
        try {
            $policy = Policy::getByType($type);

            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Policy not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Policy retrieved successfully',
                'data' => $policy,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve policy',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
