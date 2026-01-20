<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComboOffer;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ComboOfferController extends Controller
{
    /**
     * Find nearest store based on latitude and longitude
     */
    private function findNearestStore($latitude, $longitude)
    {
        if (!$latitude || !$longitude) {
            return null;
        }

        $store = \App\Models\Store::where('status', 'active')
            ->where('is_online', true)
            ->selectRaw('*, (
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance', [$latitude, $longitude, $latitude])
            ->orderBy('distance')
            ->first();

        return $store;
    }

    /**
     * Get all active combo offers
     */
    public function index(Request $request)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $nearestStore = $this->findNearestStore($latitude, $longitude);
        $storeId = $nearestStore ? $nearestStore->id : null;

        $query = ComboOffer::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->with(['items.product', 'items.productVariant']);

        $combos = $query->orderBy('priority', 'desc')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($combo) use ($storeId) {
                if (!$combo->isValid()) {
                    return false;
                }

                // Check stock availability for all items in combo
                if ($storeId) {
                    foreach ($combo->items as $item) {
                        if ($item->product_variant_id) {
                            $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                                ->where('store_id', $storeId)
                                ->first();

                            if (!$stock || $stock->quantity < $item->quantity) {
                                return false;
                            }
                        }
                    }
                }

                return true;
            })
            ->map(function($combo) use ($storeId, $nearestStore) {
                // Add stock info for each item
                if ($storeId) {
                    foreach ($combo->items as $item) {
                        if ($item->product_variant_id) {
                            $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                                ->where('store_id', $storeId)
                                ->first();
                            $item->stock = $stock;
                        }
                    }
                }
                return $combo;
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $combos,
            'nearest_store' => $nearestStore ? [
                'id' => $nearestStore->id,
                'name' => $nearestStore->name,
                'distance' => round($nearestStore->distance, 2) . ' km',
            ] : null
        ], 200);
    }

    /**
     * Get featured combo offers
     */
    public function featured(Request $request)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $limit = $request->query('limit', 10);
        $nearestStore = $this->findNearestStore($latitude, $longitude);
        $storeId = $nearestStore ? $nearestStore->id : null;

        $combos = ComboOffer::where('is_active', true)
            ->where('is_featured', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->with(['items.product', 'items.productVariant'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->filter(function($combo) use ($storeId) {
                if (!$combo->isValid()) {
                    return false;
                }

                if ($storeId) {
                    foreach ($combo->items as $item) {
                        if ($item->product_variant_id) {
                            $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                                ->where('store_id', $storeId)
                                ->first();

                            if (!$stock || $stock->quantity < $item->quantity) {
                                return false;
                            }
                        }
                    }
                }

                return true;
            })
            ->map(function($combo) use ($storeId) {
                if ($storeId) {
                    foreach ($combo->items as $item) {
                        if ($item->product_variant_id) {
                            $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                                ->where('store_id', $storeId)
                                ->first();
                            $item->stock = $stock;
                        }
                    }
                }
                return $combo;
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $combos,
            'nearest_store' => $nearestStore ? [
                'id' => $nearestStore->id,
                'name' => $nearestStore->name,
                'distance' => round($nearestStore->distance, 2) . ' km',
            ] : null
        ], 200);
    }

    /**
     * Get combo offer by slug
     */
    public function show(Request $request, $slug)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $nearestStore = $this->findNearestStore($latitude, $longitude);
        $storeId = $nearestStore ? $nearestStore->id : null;

        $combo = ComboOffer::where('slug', $slug)
            ->where('is_active', true)
            ->with(['items.product', 'items.productVariant', 'items.productVariant.prices'])
            ->firstOrFail();

        if (!$combo->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Combo offer is not currently valid'
            ], 400);
        }

        // Add stock info for each item
        if ($storeId) {
            foreach ($combo->items as $item) {
                if ($item->product_variant_id) {
                    $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                        ->where('store_id', $storeId)
                        ->first();
                    $item->stock = $stock;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $combo,
            'nearest_store' => $nearestStore ? [
                'id' => $nearestStore->id,
                'name' => $nearestStore->name,
                'distance' => round($nearestStore->distance, 2) . ' km',
            ] : null
        ], 200);
    }
}
