<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    /**
     * Get all active coupons
     */
    public function index(Request $request)
    {
        $query = Coupon::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->where(function($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            });

        $coupons = $query->orderBy('created_at', 'desc')->get();
            

        return response()->json([
            'success' => true,
            'data' => $coupons
        ], 200);
    }

    /**
     * Validate and get coupon details
     */
    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'order_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();
        $orderAmount = $request->order_amount ?? 0;

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 404);
        }

        // Check if coupon is valid
        if (!$coupon->isValid($customer ? $customer->id : null, $orderAmount)) {
            $errors = [];

            if (now()->lt($coupon->valid_from) || now()->gt($coupon->valid_to)) {
                $errors[] = 'Coupon is not valid at this time';
            }

            if ($orderAmount < $coupon->minimum_order_amount) {
                $errors[] = "Minimum order amount is ₹{$coupon->minimum_order_amount}";
            }

            if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                $errors[] = 'Coupon usage limit reached';
            }

            if ($customer) {
                $userUsageCount = $coupon->usages()
                    ->where('customer_id', $customer->id)
                    ->count();

                if ($userUsageCount >= $coupon->usage_limit_per_user) {
                    $errors[] = 'You have already used this coupon';
                }

                if ($coupon->is_first_order_only) {
                    $ordersCount = Order::where('customer_id', $customer->id)->count();
                    if ($ordersCount > 0) {
                        $errors[] = 'This coupon is only valid for your first order';
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Coupon is not valid',
                'errors' => $errors
            ], 400);
        }

        // Calculate discount
        $discountAmount = $coupon->calculateDiscount($orderAmount);

        return response()->json([
            'success' => true,
            'message' => 'Coupon is valid',
            'data' => [
                'coupon' => $coupon,
                'discount_amount' => $discountAmount,
                'order_amount' => $orderAmount,
                'final_amount' => max(0, $orderAmount - $discountAmount),
            ]
        ], 200);
    }

    /**
     * Apply coupon to order (used during checkout)
     */
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'order_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();
        $orderAmount = $request->order_amount;

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 404);
        }

        // Check if coupon is valid
        if (!$coupon->isValid($customer->id, $orderAmount)) {
            $errors = [];

            if (now()->lt($coupon->valid_from) || now()->gt($coupon->valid_to)) {
                $errors[] = 'Coupon is not valid at this time';
            }

            if ($orderAmount < $coupon->minimum_order_amount) {
                $errors[] = "Minimum order amount is ₹{$coupon->minimum_order_amount}";
            }

            if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                $errors[] = 'Coupon usage limit reached';
            }

            $userUsageCount = $coupon->usages()
                ->where('customer_id', $customer->id)
                ->count();

            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                $errors[] = 'You have already used this coupon';
            }

            if ($coupon->is_first_order_only) {
                $ordersCount = Order::where('customer_id', $customer->id)->count();
                if ($ordersCount > 0) {
                    $errors[] = 'This coupon is only valid for your first order';
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Coupon is not valid or cannot be applied',
                'errors' => $errors
            ], 400);
        }

        // Calculate discount
        $discountAmount = $coupon->calculateDiscount($orderAmount);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'data' => [
                'coupon_id' => $coupon->id,
                'coupon_code' => $coupon->code,
                'discount_amount' => $discountAmount,
                'order_amount' => $orderAmount,
                'final_amount' => max(0, $orderAmount - $discountAmount),
            ]
        ], 200);
    }

    /**
     * Get customer's coupon usage history
     */
    public function myCoupons(Request $request)
    {
        $customer = $request->user();

        $usages = CouponUsage::where('customer_id', $customer->id)
            ->with(['coupon', 'order'])
            ->orderBy('used_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $usages
        ], 200);
    }
}