<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller
{
    /**
     * Display a listing of stocks for a product.
     */
    public function index($product_id)
    {
        Product::findOrFail($product_id);

        $stocks = ProductStock::with(['productVariant', 'store', 'lastRestockedByUser'])
            ->whereHas('productVariant', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stocks
        ]);
    }

    /**
     * Store a newly created stock record.
     */
    public function store(Request $request, $product_id)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,low_stock,out_of_stock,backorder',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ensure variant belongs to product
        $variant = ProductVariant::where('product_id', $product_id)
            ->findOrFail($request->product_variant_id);

        try {
            DB::beginTransaction();

            $stock = ProductStock::where('product_variant_id', $request->product_variant_id)
                ->where('store_id', $request->store_id)
                ->first();

            $status = $request->stock_status;
            $minLevel = $request->min_stock_level ?? 0;
            $quantity = $request->quantity;

            if (!$status) {
                if ($quantity <= 0) {
                    $status = 'out_of_stock';
                } elseif ($quantity <= $minLevel) {
                    $status = 'low_stock';
                } else {
                    $status = 'in_stock';
                }
            }

            if ($stock) {
                $stock->update([
                    'quantity' => $quantity,
                    'min_stock_level' => $minLevel,
                    'max_stock_level' => $request->max_stock_level,
                    'stock_status' => $status,
                    'last_restocked_at' => now(),
                    'last_restocked_by' => auth()->id(),
                ]);
            } else {
                $stock = ProductStock::create([
                    'product_variant_id' => $request->product_variant_id,
                    'store_id' => $request->store_id,
                    'quantity' => $quantity,
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => $minLevel,
                    'max_stock_level' => $request->max_stock_level,
                    'stock_status' => $status,
                    'last_restocked_at' => now(),
                    'last_restocked_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock saved successfully',
                'data' => $stock->load(['productVariant', 'store'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified stock record.
     */
    public function update(Request $request, $product_id, $id)
    {
        $stock = ProductStock::with('productVariant')
            ->whereHas('productVariant', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,low_stock,out_of_stock,backorder',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ensure variant belongs to product
        ProductVariant::where('product_id', $product_id)
            ->findOrFail($request->product_variant_id);

        try {
            DB::beginTransaction();

            $minLevel = $request->min_stock_level ?? 0;
            $quantity = $request->quantity;
            $status = $request->stock_status;

            if (!$status) {
                if ($quantity <= 0) {
                    $status = 'out_of_stock';
                } elseif ($quantity <= $minLevel) {
                    $status = 'low_stock';
                } else {
                    $status = 'in_stock';
                }
            }

            $stock->update([
                'store_id' => $request->store_id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $quantity,
                'min_stock_level' => $minLevel,
                'max_stock_level' => $request->max_stock_level,
                'stock_status' => $status,
                'last_restocked_at' => now(),
                'last_restocked_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'data' => $stock->fresh()->load(['productVariant', 'store'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified stock record.
     */
    public function delete($product_id, $id)
    {
        try {
            $stock = ProductStock::with('productVariant')
                ->whereHas('productVariant', function ($query) use ($product_id) {
                    $query->where('product_id', $product_id);
                })
                ->findOrFail($id);

            $stock->delete();

            return response()->json([
                'success' => true,
                'message' => 'Stock deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get store-wise stock breakdown for a variant.
     */
    public function variantStocks(Request $request, $product_id, $variant_id)
    {
        $variant = ProductVariant::where('product_id', $product_id)
            ->findOrFail($variant_id);

        $type = $request->query('type', 'in_stock');
        $validTypes = ['in_stock', 'available', 'in_transit'];

        if (!in_array($type, $validTypes, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid stock type'
            ], 422);
        }

        $stocks = ProductStock::with('store')
            ->where('product_variant_id', $variant_id)
            ->get()
            ->map(function ($stock) use ($type) {
                $available = max(($stock->quantity ?? 0) - ($stock->reserved_quantity ?? 0), 0);

                $quantityByType = match ($type) {
                    'available' => $available,
                    'in_transit' => $stock->in_transit_quantity ?? 0,
                    default => $stock->quantity ?? 0,
                };

                return [
                    'store_id' => $stock->store_id,
                    'store_name' => $stock->store->name ?? '-',
                    'quantity' => $quantityByType,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $stocks
        ]);
    }
}
