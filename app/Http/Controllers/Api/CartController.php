<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\ProductStock;
use App\Models\Store;
use App\Models\ComboOffer;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Services\CartPricingService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Find nearest store based on latitude and longitude
     */
    private function findNearestStore($latitude, $longitude)
    {
        if (!$latitude || !$longitude) {
            return null;
        }

        // Haversine formula to calculate distance
        $store = Store::where('status', 'active')
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
     * Get customer's cart
     */
   public function index(Request $request, CartPricingService $pricingService)
{
    $customer = $request->user();
    $latitude = $request->query('latitude');
    $longitude = $request->query('longitude');

    $nearestStore = $this->findNearestStore($latitude, $longitude);
    $storeId = $nearestStore ? $nearestStore->id : null;

    $query = Cart::where('customer_id', $customer->id)
        ->with(['productVariant.product', 'store']);

    if ($storeId) {
        $query->where('store_id', $storeId);
    }

    $cartItems = $query->get();

    // ✅ CENTRALIZED PRICING (AppSettings based)
    $pricing = $pricingService->calculate($cartItems, $nearestStore);

    return response()->json([
        'success' => true,
        'data' => [
            'items' => $cartItems,
            'summary' => [
                'total_items'        => $cartItems->sum('quantity'),
                'subtotal'           => $pricing['subtotal'],
                'tax_percent'        => $pricing['tax_percent'],
                'tax_amount'         => $pricing['tax_amount'],
                'shipping_charge'    => $pricing['shipping_charge'],
                'grand_total'        => $pricing['grand_total'],

                // Validation helpers
                'min_order_amount'   => $pricing['min_order_amount'],
                'is_min_order_met'   => $pricing['is_min_order_met'],
                'free_delivery_above'=> $pricing['free_delivery_above'],

                'currency_symbol'    => $pricing['currency_symbol'],
            ]
        ],
        'nearest_store' => $nearestStore ? [
            'id'       => $nearestStore->id,
            'name'     => $nearestStore->name,
            'distance' => round($nearestStore->distance, 2) . ' km',
        ] : null
    ], 200);
}

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_variant_id' => 'required|exists:product_variants,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();
        
        // Find nearest store
        $nearestStore = $this->findNearestStore($request->latitude, $request->longitude);
        
        if (!$nearestStore) {
            return response()->json([
                'success' => false,
                'message' => 'No active store found near your location'
            ], 400);
        }

        $storeId = $nearestStore->id;
        $productVariant = ProductVariant::findOrFail($request->product_variant_id);

        // Check stock availability
        $stock = ProductStock::where('product_variant_id', $productVariant->id)
            ->where('store_id', $storeId)
            ->first();

        // if (!$stock || $stock->quantity < $request->quantity) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Insufficient stock available'
        //     ], 400);
        // }

        // TEMPORARY: allow cart add without stock check
if (!$stock) {
    $stockQuantity = 0;
} else {
    $stockQuantity = $stock->quantity;
}

        // Get current price
        $currentPrice = $productVariant->prices()
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('effective_from')
                  ->orWhere('effective_from', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('effective_to')
                  ->orWhere('effective_to', '>=', now());
            })
            ->orderBy('effective_from', 'desc')
            ->first();

        if (!$currentPrice) {
            return response()->json([
                'success' => false,
                'message' => 'Product price not available'
            ], 400);
        }

        $unitPrice = $currentPrice->price;
        $totalPrice = $unitPrice * $request->quantity;

        // Check if item already exists in cart
        $cartItem = Cart::where('customer_id', $customer->id)
            ->where('product_variant_id', $productVariant->id)
            ->where('store_id', $storeId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // if ($stock->quantity < $newQuantity) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Insufficient stock available'
            //     ], 400);
            // }

            $cartItem->update([
                'quantity' => $newQuantity,
                'total_price' => $unitPrice * $newQuantity,
            ]);
        } else {
            // Create new cart item
            $cartItem = Cart::create([
                'customer_id' => $customer->id,
                'product_variant_id' => $productVariant->id,
                'store_id' => $storeId,
                'quantity' => $request->quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'notes' => $request->notes,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => $cartItem->load(['productVariant.product', 'store']),
            'nearest_store' => [
                'id' => $nearestStore->id,
                'name' => $nearestStore->name,
                'distance' => round($nearestStore->distance, 2) . ' km',
            ]
        ], 201);
    }

    /**
     * Update cart item
     */
   public function update(Request $request, $id, CartPricingService $pricingService)
{
    $validator = Validator::make($request->all(), [
        'quantity' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $customer = $request->user();
    $cartItem = Cart::where('id', $id)
        ->where('customer_id', $customer->id)
        ->firstOrFail();

    // Stock check
    $stock = ProductStock::where('product_variant_id', $cartItem->product_variant_id)
        ->where('store_id', $cartItem->store_id)
        ->first();

    if (!$stock || $stock->quantity < $request->quantity) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient stock available'
        ], 400);
    }

    // Update cart item
    $cartItem->update([
        'quantity' => $request->quantity,
        'total_price' => $cartItem->unit_price * $request->quantity,
    ]);

    // 🔥 Recalculate cart pricing
    $cartItems = Cart::where('customer_id', $customer->id)
        ->where('store_id', $cartItem->store_id)
        ->get();

    $pricing = $pricingService->calculate($cartItems, $cartItem->store);

    return response()->json([
        'success' => true,
        'message' => 'Cart updated successfully',
        'data' => [
            'item' => $cartItem->load(['productVariant.product', 'store']),
            'summary' => [
                'total_items'        => $cartItems->sum('quantity'),
                'subtotal'           => $pricing['subtotal'],
                'tax_percent'        => $pricing['tax_percent'],
                'tax_amount'         => $pricing['tax_amount'],
                'shipping_charge'    => $pricing['shipping_charge'],
                'grand_total'        => $pricing['grand_total'],
                'min_order_amount'   => $pricing['min_order_amount'],
                'is_min_order_met'   => $pricing['is_min_order_met'],
                'free_delivery_above'=> $pricing['free_delivery_above'],
                'currency_symbol'    => $pricing['currency_symbol'],
            ]
        ]
    ], 200);
}

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, $id)
    {
        $customer = $request->user();
        $cartItem = Cart::where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ], 200);
    }

    /**
     * Clear cart
     */
    public function clear(Request $request)
    {
        $customer = $request->user();
        Cart::where('customer_id', $customer->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ], 200);
    }

    /**
     * Add combo offer to cart
     */
    public function addCombo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'combo_offer_id' => 'required|exists:combo_offers,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();
        
        // Find nearest store
        $nearestStore = $this->findNearestStore($request->latitude, $request->longitude);
        
        if (!$nearestStore) {
            return response()->json([
                'success' => false,
                'message' => 'No active store found near your location'
            ], 400);
        }

        $storeId = $nearestStore->id;
        $comboOffer = ComboOffer::with('items.productVariant')->findOrFail($request->combo_offer_id);

        // Validate combo offer
        if (!$comboOffer->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Combo offer is not currently valid'
            ], 400);
        }

        if (!$comboOffer->isAvailable($request->quantity)) {
            return response()->json([
                'success' => false,
                'message' => 'Combo offer is out of stock'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $addedItems = [];

            // Add each item from combo to cart
            foreach ($comboOffer->items as $comboItem) {
                $productVariant = $comboItem->productVariant;

                // Check stock availability
                $stock = ProductStock::where('product_variant_id', $productVariant->id)
                    ->where('store_id', $storeId)
                    ->first();

                $requiredQuantity = $comboItem->quantity * $request->quantity;

                if (!$stock || $stock->quantity < $requiredQuantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$productVariant->product->name}"
                    ], 400);
                }

                // Get current price
                $currentPrice = $productVariant->prices()
                    ->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
                    })
                    ->orderBy('effective_from', 'desc')
                    ->first();

                if (!$currentPrice) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Price not available for {$productVariant->product->name}"
                    ], 400);
                }

                $unitPrice = $currentPrice->price;
                $itemQuantity = $comboItem->quantity * $request->quantity;
                $totalPrice = $unitPrice * $itemQuantity;

                // Check if item already exists in cart
                $cartItem = Cart::where('customer_id', $customer->id)
                    ->where('product_variant_id', $productVariant->id)
                    ->where('store_id', $storeId)
                    ->first();

                if ($cartItem) {
                    // Update quantity
                    $newQuantity = $cartItem->quantity + $itemQuantity;
                    
                    if ($stock->quantity < $newQuantity) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Insufficient stock available'
                        ], 400);
                    }

                    $cartItem->update([
                        'quantity' => $newQuantity,
                        'total_price' => $unitPrice * $newQuantity,
                    ]);
                    $addedItems[] = $cartItem;
                } else {
                    // Create new cart item
                    $cartItem = Cart::create([
                        'customer_id' => $customer->id,
                        'product_variant_id' => $productVariant->id,
                        'store_id' => $storeId,
                        'quantity' => $itemQuantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);
                    $addedItems[] = $cartItem;
                }
            }

            // Update combo offer sold quantity
            $comboOffer->increment('sold_quantity', $request->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Combo offer added to cart successfully',
                'data' => [
                    'combo_offer' => $comboOffer,
                    'cart_items' => collect($addedItems)->load(['productVariant.product', 'store']),
                ],
                'nearest_store' => [
                    'id' => $nearestStore->id,
                    'name' => $nearestStore->name,
                    'distance' => round($nearestStore->distance, 2) . ' km',
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add combo offer to cart: ' . $e->getMessage()
            ], 500);
        }
    }
}