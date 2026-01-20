<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of prices for a product.
     */
    public function index($product_id)
    {
        $product = Product::findOrFail($product_id);
        
        // Get all prices with their variants
        $prices = ProductPrice::with('productVariant')
            ->whereHas('productVariant', function($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prices
        ]);
    }

    /**
     * Store a newly created price.
     */
    public function store(Request $request, $product_id)
    {
        // Convert string/integer booleans to actual booleans
        $request->merge([
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : null,
        ]);

        $validator = Validator::make($request->all(), [
            'product_variant_id' => 'required|exists:product_variants,id',
            'cost_price' => 'nullable|numeric|min:0|max:9999999.99',
            'compare_at_price' => 'nullable|numeric|min:0|max:9999999.99',
            'price' => 'required|numeric|min:0|max:9999999.99',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify variant belongs to the product
        $variant = ProductVariant::where('product_id', $product_id)
            ->findOrFail($request->product_variant_id);

        try {
            DB::beginTransaction();

            // Calculate discount percentage if not provided
            $discountPercentage = $request->discount_percentage;
            if (!$discountPercentage && $request->compare_at_price && $request->compare_at_price > $request->price) {
                $discountPercentage = (($request->compare_at_price - $request->price) / $request->compare_at_price) * 100;
            }

            $price = ProductPrice::create([
                'product_variant_id' => $request->product_variant_id,
                'cost_price' => $request->cost_price,
                'compare_at_price' => $request->compare_at_price,
                'price' => $request->price,
                'discount_percentage' => $discountPercentage ? round($discountPercentage, 2) : null,
                'effective_from' => $request->effective_from,
                'effective_to' => $request->effective_to,
                'is_active' => $request->is_active ?? true,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Price created successfully',
                'data' => $price->load('productVariant')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create price',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified price.
     */
    public function update(Request $request, $product_id, $id)
    {
        $price = ProductPrice::whereHas('productVariant', function($query) use ($product_id) {
            $query->where('product_id', $product_id);
        })->findOrFail($id);

        // Convert string/integer booleans to actual booleans
        $request->merge([
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : null,
        ]);

        $validator = Validator::make($request->all(), [
            'product_variant_id' => 'required|exists:product_variants,id',
            'cost_price' => 'nullable|numeric|min:0|max:9999999.99',
            'compare_at_price' => 'nullable|numeric|min:0|max:9999999.99',
            'price' => 'required|numeric|min:0|max:9999999.99',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify variant belongs to the product
        $variant = ProductVariant::where('product_id', $product_id)
            ->findOrFail($request->product_variant_id);

        try {
            DB::beginTransaction();

            // Calculate discount percentage if not provided
            $discountPercentage = $request->discount_percentage;
            if (!$discountPercentage && $request->compare_at_price && $request->compare_at_price > $request->price) {
                $discountPercentage = (($request->compare_at_price - $request->price) / $request->compare_at_price) * 100;
            }

            $price->update([
                'product_variant_id' => $request->product_variant_id,
                'cost_price' => $request->cost_price,
                'compare_at_price' => $request->compare_at_price,
                'price' => $request->price,
                'discount_percentage' => $discountPercentage ? round($discountPercentage, 2) : null,
                'effective_from' => $request->effective_from,
                'effective_to' => $request->effective_to,
                'is_active' => $request->is_active ?? true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Price updated successfully',
                'data' => $price->fresh()->load('productVariant')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update price',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified price.
     */
    public function delete($product_id, $id)
    {
        try {
            $price = ProductPrice::whereHas('productVariant', function($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })->findOrFail($id);

            $price->delete();

            return response()->json([
                'success' => true,
                'message' => 'Price deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete price',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
