<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RecentlyViewedProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecentlyViewedController extends Controller
{
    /**
     * Get recently viewed products for authenticated customer
     */

    public function index(Request $request)
    {
        try {
            $customer = $request->user();
            $storeId  = $request->query('store_id'); // optional

            $perPage = $request->get('per_page', 20);
            $limit   = $request->get('limit', 50);

            $recentlyViewed = RecentlyViewedProduct::where('customer_id', $customer->id)
                ->with([
                    'product.category',
                    'product.brand',
                    'product.images' => fn ($q) => $q->where('is_primary', true),
                    'product.variants' => function ($q) use ($storeId) {
                        $q->where('is_active', true)
                        ->orderBy('sort_order')
                        ->with([
                            'prices' => function ($q2) {
                                $q2->where('is_active', true)
                                    ->where(function ($q) {
                                        $q->whereNull('effective_from')
                                        ->orWhere('effective_from', '<=', now());
                                    })
                                    ->where(function ($q) {
                                        $q->whereNull('effective_to')
                                        ->orWhere('effective_to', '>=', now());
                                    })
                                    ->orderBy('effective_from', 'desc');
                            },
                            'stocks' => function ($q2) use ($storeId) {
                                if ($storeId) {
                                    $q2->where('store_id', $storeId);
                                }
                            }
                        ]);
                    }
                ])
                ->orderBy('viewed_at', 'desc')
                ->limit($limit)
                ->paginate($perPage);

            // Attach price & stock like Featured API
            $recentlyViewed->getCollection()->transform(function ($item) {
                $item->product->variants->each(function ($variant) {
                    $variant->current_price = $variant->prices->first();
                    $variant->stock = $variant->stocks->first();

                    unset($variant->prices, $variant->stocks);
                });

                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Recently viewed products retrieved successfully',
                'data' => $recentlyViewed,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recently viewed products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Add product to recently viewed
     * This should be called when a customer views a product
     * 
     * POST /api/v1/recently-viewed
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
        $storeId  = $request->query('store_id'); // optional

        $product = Product::where('id', $request->product_id)
            ->where('status', 'active')
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found or not available'
            ], 404);
        }

        $recentlyViewed = RecentlyViewedProduct::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'product_id'  => $request->product_id,
            ],
            [
                'viewed_at' => now(),
            ]
        );

        $this->limitRecentlyViewed($customer->id);

        $recentlyViewed->load([
            'product.category',
            'product.brand',
            'product.images' => fn ($q) => $q->where('is_primary', true),
            'product.variants' => function ($q) use ($storeId) {
                $q->where('is_active', true)
                  ->orderBy('sort_order')
                  ->with([
                      'prices' => function ($q2) {
                          $q2->where('is_active', true)
                             ->where(function ($q) {
                                 $q->whereNull('effective_from')
                                   ->orWhere('effective_from', '<=', now());
                             })
                             ->where(function ($q) {
                                 $q->whereNull('effective_to')
                                   ->orWhere('effective_to', '>=', now());
                             })
                             ->orderBy('effective_from', 'desc');
                      },
                      'stocks' => function ($q2) use ($storeId) {
                          if ($storeId) {
                              $q2->where('store_id', $storeId);
                          }
                      }
                  ]);
            }
        ]);

        // Attach price & stock
        $recentlyViewed->product->variants->each(function ($variant) {
            $variant->current_price = $variant->prices->first();
            $variant->stock = $variant->stocks->first();

            unset($variant->prices, $variant->stocks);
        });

        return response()->json([
            'success' => true,
            'message' => 'Product added to recently viewed',
            'data' => $recentlyViewed,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to add product to recently viewed',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * Clear all recently viewed products
     * 
     * DELETE /api/v1/recently-viewed
     */
    public function clear(Request $request)
    {
        try {
            $customer = $request->user();

            $deleted = RecentlyViewedProduct::where('customer_id', $customer->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Recently viewed products cleared successfully',
                'data' => [
                    'deleted_count' => $deleted,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear recently viewed products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove a specific product from recently viewed
     * 
     * DELETE /api/v1/recently-viewed/{id}
     */
    public function destroy(Request $request, $id)
    {
        try {
            $customer = $request->user();

            $recentlyViewed = RecentlyViewedProduct::where('id', $id)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$recentlyViewed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recently viewed item not found'
                ], 404);
            }

            $recentlyViewed->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from recently viewed successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from recently viewed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Limit recently viewed products to last 50 per customer
     */
    private function limitRecentlyViewed($customerId)
    {
        $count = RecentlyViewedProduct::where('customer_id', $customerId)->count();
        
        if ($count > 50) {
            // Get IDs of oldest records to delete
            $oldestRecords = RecentlyViewedProduct::where('customer_id', $customerId)
                ->orderBy('viewed_at', 'asc')
                ->limit($count - 50)
                ->pluck('id');
            
            RecentlyViewedProduct::whereIn('id', $oldestRecords)->delete();
        }
    }
}