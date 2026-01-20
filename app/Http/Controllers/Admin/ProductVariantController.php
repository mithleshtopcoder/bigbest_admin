<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of variants for a product.
     */
    public function index($product_id)
    {
        $product = Product::findOrFail($product_id);
        $variants = ProductVariant::where('product_id', $product_id)
            ->withCount('images')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $variants
        ]);
    }

    /**
     * Store a newly created variant.
     */
    public function store(Request $request, $product_id)
    {
        // Convert string/integer booleans to actual booleans
        $request->merge([
            'is_default' => $request->exists('is_default')
                ? filter_var($request->input('is_default'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
            'is_active' => $request->exists('is_active')
                ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
            'is_featured' => $request->exists('is_featured')
                ? filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:100|unique:product_variants,sku',
            'barcode' => 'nullable|string|max:100',
            'unit' => 'required|in:piece,kg,gram,liter,ml,bundle,bunch,packet,box,bottle,can,dozen,pack,loose',
            'unit_value' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // If this is set as default, unset other defaults
            if ($request->is_default) {
                ProductVariant::where('product_id', $product_id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $variant = ProductVariant::create([
                'product_id' => $product_id,
                'name' => $request->name,
                'code' => $request->code,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'unit' => $request->unit,
                'unit_value' => $request->unit_value,
                'is_default' => $request->is_default ?? false,
                'is_active' => $request->is_active ?? true,
                'is_featured' => $request->is_featured ?? false,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Variant created successfully',
                'data' => $variant
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create variant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified variant.
     */
    public function update(Request $request, $product_id, $id)
    {
        $variant = ProductVariant::where('product_id', $product_id)
            ->findOrFail($id);

        // Convert string/integer booleans to actual booleans
        $request->merge([
            'is_default' => $request->exists('is_default')
                ? filter_var($request->input('is_default'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
            'is_active' => $request->exists('is_active')
                ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
            'is_featured' => $request->exists('is_featured')
                ? filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:100|unique:product_variants,sku,' . $id,
            'barcode' => 'nullable|string|max:100',
            'unit' => 'required|in:piece,kg,gram,liter,ml,bundle,bunch,packet,box,bottle,can,dozen,pack,loose',
            'unit_value' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // If this is set as default, unset other defaults
            if ($request->is_default && !$variant->is_default) {
                ProductVariant::where('product_id', $product_id)
                    ->where('id', '!=', $id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $variant->update([
                'name' => $request->name,
                'code' => $request->code,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'unit' => $request->unit,
                'unit_value' => $request->unit_value,
                'is_default' => $request->is_default ?? false,
                'is_active' => $request->is_active ?? true,
                'is_featured' => $request->is_featured ?? false,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Variant updated successfully',
                'data' => $variant->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update variant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified variant.
     */
    public function delete($product_id, $id)
    {
        try {
            $variant = ProductVariant::where('product_id', $product_id)
                ->findOrFail($id);

            $variant->delete();

            return response()->json([
                'success' => true,
                'message' => 'Variant deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete variant',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
