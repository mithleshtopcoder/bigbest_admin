<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Get all active offers
     */
    public function index(Request $request)
    {
        $offerType = $request->query('type'); // product, category, brand, cart
        $productId = $request->query('product_id');
        $categoryId = $request->query('category_id');
        $brandId = $request->query('brand_id');

        $query = Offer::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now());

        // Filter by offer type
        if ($offerType) {
            $query->where('offer_type', $offerType);
        }

        // Filter by product
        if ($productId) {
            $query->where(function($q) use ($productId) {
                $q->where('offer_type', 'product')
                  ->where('product_id', $productId)
                  ->orWhere('offer_type', 'category')
                  ->orWhere('offer_type', 'brand')
                  ->orWhere('offer_type', 'cart');
            });
        }

        // Filter by category
        if ($categoryId) {
            $query->where(function($q) use ($categoryId) {
                $q->where('offer_type', 'category')
                  ->where('category_id', $categoryId)
                  ->orWhere('offer_type', 'cart');
            });
        }

        // Filter by brand
        if ($brandId) {
            $query->where(function($q) use ($brandId) {
                $q->where('offer_type', 'brand')
                  ->where('brand_id', $brandId)
                  ->orWhere('offer_type', 'cart');
            });
        }

        $offers = $query->orderBy('priority', 'desc')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($offer) {
                return $offer->isValid();
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $offers
        ], 200);
    }

    /**
     * Get featured offers
     */
    public function featured(Request $request)
    {
        $limit = $request->query('limit', 10);

        $offers = Offer::where('is_active', true)
            ->where('is_featured', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->filter(function($offer) {
                return $offer->isValid();
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $offers
        ], 200);
    }

    /**
     * Get offer by slug
     */
    public function show(Request $request, $slug)
    {
        $offer = Offer::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if (!$offer->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Offer is not currently valid'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $offer
        ], 200);
    }

    /**
     * Get applicable offers for cart/order
     */
    public function applicable(Request $request)
    {
        $cartItems = $request->input('cart_items', []); // Array of product/category/brand IDs
        $cartAmount = $request->input('cart_amount', 0);

        $offers = Offer::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->where(function($q) use ($cartAmount) {
                $q->where('minimum_order_amount', '<=', $cartAmount)
                  ->orWhereNull('minimum_order_amount');
            })
            ->orderBy('priority', 'desc')
            ->get()
            ->filter(function($offer) use ($cartItems) {
                if (!$offer->isValid()) {
                    return false;
                }

                // Check if offer applies to cart items
                if ($offer->offer_type === 'cart') {
                    return true;
                }

                // Check product/category/brand matches
                foreach ($cartItems as $item) {
                    if ($offer->offer_type === 'product' && isset($item['product_id']) && $item['product_id'] == $offer->product_id) {
                        return true;
                    }
                    if ($offer->offer_type === 'category' && isset($item['category_id']) && $item['category_id'] == $offer->category_id) {
                        return true;
                    }
                    if ($offer->offer_type === 'brand' && isset($item['brand_id']) && $item['brand_id'] == $offer->brand_id) {
                        return true;
                    }
                }

                return false;
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $offers
        ], 200);
    }
}
