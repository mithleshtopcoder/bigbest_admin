<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Get all wishlist items for authenticated customer
     */
public function index(Request $request)
{
    try {
        $customer = $request->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)
            ->with([
                    'product' => function ($q) {
                        $q->select([
                            'id',
                            'name',
                            'slug',
                            'thumbnail_image',
                            'category_id',
                            'brand_id',
                            'status',
                            'is_featured',
                            'rating',
                            'total_reviews'
                        ]);
                    },
                    'product.category' => function ($q) {
                        $q->select('id', 'name', 'slug');
                    },
                    'product.brand' => function ($q) {
                        $q->select('id', 'name', 'slug');
                },
                'product.variants' => function ($q) {
                    $q->where('is_active', 1)
                          ->select([
                              'id',
                              'product_id',
                              'name',
                              'variant_type',
                              'variant_value',
                              'unit',
                              'is_default',
                              'sort_order'
                          ])
                          ->orderBy('is_default', 'desc')
                          ->orderBy('sort_order', 'asc')
                          ->limit(1)
                      ->with([
                          'prices' => function ($priceQ) {
                              $priceQ->where('is_active', 1)
                                     ->select(
                                         'id',
                                         'product_variant_id',
                                         'price',
                                         'compare_at_price',
                                         'discount_percentage'
                                         )
                                         ->orderBy('id', 'desc')
                                         ->limit(1);
                          }
                      ]);
                }
            ])
                ->select('id', 'customer_id', 'product_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

            // Transform the response to include only required fields
            $wishlist->getCollection()->transform(function ($item) {
                $product = $item->product;
                
                // Skip if product is deleted or doesn't exist
                if (!$product) {
                    return [
                        'id' => $item->id,
                        'product_id' => null,
                        'product_name' => 'Product no longer available',
                        'product_slug' => null,
                        'thumbnail_image' => null,
                        'price' => null,
                        'compare_at_price' => null,
                        'discount_percentage' => 0,
                        'variant_name' => null,
                        'variant_unit' => null,
                        'category' => null,
                        'brand' => null,
                        'rating' => 0,
                        'total_reviews' => 0,
                        'is_featured' => false,
                        'status' => 'deleted',
                        'added_at' => $item->created_at->toISOString(),
                    ];
                }

                $defaultVariant = $product->variants->first();
                $price = $defaultVariant ? $defaultVariant->prices->first() : null;

                return [
                    'id' => $item->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_slug' => $product->slug,
                    'thumbnail_image' => $product->thumbnail_image,
                    'price' => $price ? (float) $price->price : null,
                    'compare_at_price' => $price ? ($price->compare_at_price ? (float) $price->compare_at_price : null) : null,
                    'discount_percentage' => $price ? ($price->discount_percentage ? (float) $price->discount_percentage : 0) : 0,
                    'variant_name' => $defaultVariant ? $defaultVariant->name : null,
                    'variant_unit' => $defaultVariant ? $defaultVariant->unit : null,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ] : null,
                    'brand' => $product->brand ? [
                        'id' => $product->brand->id,
                        'name' => $product->brand->name,
                        'slug' => $product->brand->slug,
                    ] : null,
                    'rating' => (float) $product->rating,
                    'total_reviews' => (int) $product->total_reviews,
                    'is_featured' => (bool) $product->is_featured,
                    'status' => $product->status,
                    'added_at' => $item->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Wishlist retrieved successfully',
            'data' => $wishlist,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve wishlist',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * Add product to wishlist
     * 
     * POST /api/v1/wishlist
     * 
     * Request Body (JSON):
     * {
     *     "product_id": 1
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customer = $request->user();

            // Check if product exists and is active
            $product = Product::where('id', $request->product_id)
                ->where('status', 'active')
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or not available'
                ], 404);
            }

            // Check if already in wishlist
            $existingWishlist = Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingWishlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product already in wishlist'
                ], 400);
            }

            // Add to wishlist
            $wishlist = Wishlist::create([
                'customer_id' => $customer->id,
                'product_id' => $request->product_id,
            ]);

            $wishlist->load([
                'product.category',
                'product.brand',
                'product.images' => function($q) {
                    $q->where('is_primary', true);
                }
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist successfully',
                'data' => $wishlist,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove product from wishlist
     * 
     * DELETE /api/v1/wishlist/{id}
     */
    public function destroy(Request $request, $id)
    {
        try {
            $customer = $request->user();

            $wishlist = Wishlist::where('id', $id)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$wishlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wishlist item not found'
                ], 404);
            }

            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove product from wishlist by product_id
     * 
     * DELETE /api/v1/wishlist/product/{product_id}
     */
    public function removeByProduct(Request $request, $productId)
    {
        try {
            $customer = $request->user();

            $wishlist = Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $productId)
                ->first();

            if (!$wishlist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in wishlist'
                ], 404);
            }

            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if product is in wishlist
     * 
     * GET /api/v1/wishlist/check/{product_id}
     */
    public function check(Request $request, $productId)
    {
        try {
            $customer = $request->user();

            $wishlist = Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $productId)
                ->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_in_wishlist' => $wishlist,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear all wishlist items
     * 
     * DELETE /api/v1/wishlist
     */
    public function clear(Request $request)
    {
        try {
            $customer = $request->user();

            $deleted = Wishlist::where('customer_id', $customer->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared successfully',
                'data' => [
                    'deleted_count' => $deleted,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}