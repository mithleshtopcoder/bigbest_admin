<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use App\Models\ProductReview;
use App\Models\Store;
use App\Models\StoreServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return null;
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    /**
     * Find stores serving a location based on service area radius
     * Checks pincode/city first (faster), then falls back to radius calculation
     * 
     * @param float|null $latitude
     * @param float|null $longitude
     * @param string|null $pincode
     * @param string|null $city
     * @return \Illuminate\Support\Collection
     */
    private function findStoresServingLocation($latitude = null, $longitude = null, $pincode = null, $city = null)
    {
        // Get all active online stores with their service areas
        $stores = Store::where('status', 'active')
            ->where('is_online', true)
            ->with(['serviceAreas' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();

        $servingStores = collect();

        foreach ($stores as $store) {
            $matched = false;
            $matchedServiceArea = null;
            $distance = null;

            // First, try to match by pincode or city (faster, no calculation needed)
            if ($pincode || $city) {
                foreach ($store->serviceAreas as $serviceArea) {
                    if (($pincode && $serviceArea->pincode === $pincode) || 
                        ($city && $serviceArea->city === $city)) {
                        $matched = true;
                        $matchedServiceArea = $serviceArea;
                        break;
                    }
                }
            }

            // If not matched by pincode/city, check by radius using coordinates
            if (!$matched && $latitude && $longitude && $store->latitude && $store->longitude) {
                $distance = $this->calculateDistance(
                    $latitude,
                    $longitude,
                    $store->latitude,
                    $store->longitude
                );

                if ($distance !== null) {
                    foreach ($store->serviceAreas as $serviceArea) {
                        if ($distance <= $serviceArea->radius_km) {
                            $matched = true;
                            $matchedServiceArea = $serviceArea;
                            break;
                        }
                    }
                }
            }

            // If store serves this location, add it to results
            if ($matched && $matchedServiceArea) {
                // Set distance to 0 if matched by pincode/city (prioritize exact matches)
                $store->distance = $distance ?? 0;
                $store->service_area = $matchedServiceArea;
                $servingStores->push($store);
            }
        }

        // Sort by distance (nearest first), stores matched by pincode/city (distance=0) come first
        return $servingStores->sortBy('distance')->values();
    }

    /**
     * Find nearest store based on latitude and longitude
     * Now uses service area radius to ensure store actually serves the location
     * 
     * @param float|null $latitude
     * @param float|null $longitude
     * @param string|null $pincode Optional pincode for faster matching
     * @param string|null $city Optional city for faster matching
     * @return Store|null
     */
    private function findNearestStore($latitude = null, $longitude = null, $pincode = null, $city = null)
    {
        // Get stores that serve this location
        $stores = $this->findStoresServingLocation($latitude, $longitude, $pincode, $city);

        // Return the nearest one (first in sorted collection)
        return $stores->first();
    }

    /**
     * Get list of products with filters
     */
public function index(Request $request)
{
    $latitude = $request->query('latitude');
    $longitude = $request->query('longitude');
    $pincode = $request->query('pincode');
    $city = $request->query('city');
    $nearestStore = $this->findNearestStore($latitude, $longitude, $pincode, $city);
    $storeId = $nearestStore ? $nearestStore->id : null;

    // Get filter parameters from request
    $categoryId = $request->query('category_id');
    $subCategoryId = $request->query('sub_category_id');
    $brandId = $request->query('brand_id');
    $isOrganic = $request->query('is_organic');
    $search = $request->query('search');
    $sortBy = $request->query('sort_by', 'latest');
    $perPage = $request->query('per_page', 20);

    $query = Product::where('status', 'active')
            ->select(['id', 'name', 'slug', 'thumbnail_image', 'unit', 'is_organic', 'created_at', 'rating', 'total_reviews'])
            ->with(['images' => function($q) {
                $q->select(['id', 'product_id', 'image_path', 'is_primary', 'sort_order'])
                  ->orderBy('sort_order')
                  ->limit(1);
            }]);

        // Filter by category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter by sub-category
        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        // Filter by brand
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        // Filter by organic
        if ($isOrganic !== null) {
            $query->where('is_organic', filter_var($isOrganic, FILTER_VALIDATE_BOOLEAN));
        }

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Store-based filtering (only show products available in store)
        if ($storeId) {
            $query->whereHas('variants', function($q) use ($storeId) {
                $q->whereHas('stocks', function($q2) use ($storeId) {
                    $q2->where('store_id', $storeId)
                       ->where('quantity', '>', 0);
                });
            });
        }

        // Sorting
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('(SELECT MIN(price) FROM product_prices WHERE product_prices.product_variant_id IN (SELECT id FROM product_variants WHERE product_variants.product_id = products.id) AND product_prices.is_active = 1) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(SELECT MAX(price) FROM product_prices WHERE product_prices.product_variant_id IN (SELECT id FROM product_variants WHERE product_variants.product_id = products.id) AND product_prices.is_active = 1) DESC');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc')->orderBy('total_reviews', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate($perPage);

        // Get all product IDs for bulk rating calculation
        $productIds = $products->pluck('id')->toArray();
        
        // Calculate ratings for all products in one query
        $ratingsData = collect();
        if (!empty($productIds)) {
            $ratingsData = ProductReview::whereIn('product_id', $productIds)
                ->where('status', 'approved')
                ->selectRaw('product_id, COUNT(*) as total_reviews, AVG(rating) as average_rating')
                ->groupBy('product_id')
                ->get()
                ->mapWithKeys(function($item) {
                    return [(int) $item->product_id => $item];
                });
        }

        // Transform products to include only minimal fields for listing
        $products->getCollection()->transform(function($product) use ($storeId, $ratingsData) {
            // Get primary image
            $primaryImage = $product->images->where('is_primary', true)->first();
            $imagePath = $primaryImage ? $primaryImage->image_path : ($product->images->first() ? $product->images->first()->image_path : $product->thumbnail_image);

            // Load only first active variant (for listing display) with minimal fields
            $product->load(['variants' => function($q) {
                $q->select(['id', 'product_id', 'unit', 'is_active', 'sort_order'])
                  ->where('is_active', true)
                  ->orderBy('sort_order')
                  ->limit(1);
            }]);

            // Get variant with price and stock
            $variant = $product->variants->first();
            $currentPrice = null;
            $stockQuantity = 0;
            $unit = null;
            $discountPercentage = 0;

            if ($variant) {
                $currentPrice = $variant->prices()
                    ->select(['id', 'product_variant_id', 'price', 'compare_at_price', 'is_active', 'effective_from', 'effective_to'])
                    ->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
                    })
                    ->orderBy('effective_from', 'desc')
                    ->first();

                // Get stock if store_id provided
                if ($storeId && $currentPrice) {
                    $stock = ProductStock::select(['id', 'product_variant_id', 'store_id', 'quantity'])
                        ->where('product_variant_id', $variant->id)
                        ->where('store_id', $storeId)
                        ->first();
                    $stockQuantity = $stock ? $stock->quantity : 0;
                }

                $unit = $variant->unit ?? $product->unit;
                
                // Calculate discount percentage
                if ($currentPrice && $currentPrice->compare_at_price && $currentPrice->compare_at_price > $currentPrice->price) {
                    $discountPercentage = round((($currentPrice->compare_at_price - $currentPrice->price) / $currentPrice->compare_at_price) * 100, 0);
                }
            }

            // Set rating and total_reviews from bulk calculation
            $ratingInfo = $ratingsData->get($product->id);
            
            // Use calculated rating if available, otherwise fallback to product's cached rating
            if ($ratingInfo && $ratingInfo->total_reviews > 0) {
                $totalReviews = (int) $ratingInfo->total_reviews;
                $rating = $ratingInfo->average_rating !== null 
                    ? (float) round((float) $ratingInfo->average_rating, 2) 
                    : 0.00;
            } else {
                // Fallback to product's cached rating and total_reviews
                $totalReviews = $product->total_reviews ?? 0;
                $rating = $product->rating ?? 0.00;
            }

            // Return only minimal fields needed for listing
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $imagePath,
                'rating' => $rating,
                'total_reviews' => $totalReviews,
                'price' => $currentPrice ? (float) $currentPrice->price : null,
                'compare_at_price' => $currentPrice && $currentPrice->compare_at_price ? (float) $currentPrice->compare_at_price : null,
                'discount_percentage' => $discountPercentage,
                'unit' => $unit,
                'quantity' => $stockQuantity,
                'is_organic' => $product->is_organic,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $products,
        'nearest_store' => $nearestStore ? [
            'id' => $nearestStore->id,
            'name' => $nearestStore->name,
            'distance' => round($nearestStore->distance, 2) . ' km',
        ] : null
    ], 200);
}


    /**
     * Get featured products
     */
    public function featured(Request $request)
{
    $latitude = $request->query('latitude');
    $longitude = $request->query('longitude');
    $pincode = $request->query('pincode');
    $city = $request->query('city');
    $nearestStore = $this->findNearestStore($latitude, $longitude, $pincode, $city);
    $storeId = $nearestStore ? $nearestStore->id : null;
    $limit = $request->query('limit', 10);

    $query = Product::where('status', 'active')
        ->where('is_featured', true)
        ->with([
            'category',
            'subCategory',
            'brand',
            'images' => function ($q) {
                $q->where('is_primary', true);
            }
        ]);

    if ($storeId) {
        $query->whereHas('variants', function ($q) use ($storeId) {
            $q->whereHas('stocks', function ($q2) use ($storeId) {
                $q2->where('store_id', $storeId)
                   ->where('quantity', '>', 0);
            });
        });
    }

    $products = $query->orderBy('sort_order')
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();

    $products->each(function ($product) use ($storeId) {

        $product->load(['variants' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }]);

        foreach ($product->variants as $variant) {
            $variant->current_price = $variant->prices()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('effective_from')
                      ->orWhere('effective_from', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('effective_to')
                      ->orWhere('effective_to', '>=', now());
                })
                ->orderBy('effective_from', 'desc')
                ->first();

            if ($storeId) {
                $variant->stock = ProductStock::where('product_variant_id', $variant->id)
                    ->where('store_id', $storeId)
                    ->first();
            }
        }
    });

    return response()->json([
        'success' => true,
        'data' => $products,
        'nearest_store' => $nearestStore
    ], 200);
}

    /**
     * Get products by category
     */
    public function byCategory(Request $request, $categorySlug)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $pincode = $request->query('pincode');
        $city = $request->query('city');
        $nearestStore = $this->findNearestStore($latitude, $longitude, $pincode, $city);
        $storeId = $nearestStore ? $nearestStore->id : null;
        $perPage = $request->query('per_page', 20);

        $query = Product::where('status', 'active')
            ->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->with(['category', 'subCategory', 'brand', 'images' => function($q) {
                $q->where('is_primary', true);
            }]);

        // Store-based filtering
        if ($storeId) {
            $query->whereHas('variants', function($q) use ($storeId) {
                $q->whereHas('stocks', function($q2) use ($storeId) {
                    $q2->where('store_id', $storeId)
                       ->where('quantity', '>', 0);
                });
            });
        }

        $products = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Add variant and price information
        $products->getCollection()->transform(function($product) use ($storeId, $nearestStore) {
            $product->load(['variants' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }]);

            foreach ($product->variants as $variant) {
                $currentPrice = $variant->prices()
                    ->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
                    })
                    ->orderBy('effective_from', 'desc')
                    ->first();

                $variant->current_price = $currentPrice;

                if ($storeId) {
                    $stock = ProductStock::where('product_variant_id', $variant->id)
                        ->where('store_id', $storeId)
                        ->first();
                    $variant->stock = $stock;
                }
            }

            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $products,
            'nearest_store' => $nearestStore ? [
                'id' => $nearestStore->id,
                'name' => $nearestStore->name,
                'distance' => round($nearestStore->distance, 2) . ' km',
            ] : null
        ], 200);
    }

    /**
     * Get products by brand
     */
    public function byBrand(Request $request, $brandSlug)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $pincode = $request->query('pincode');
        $city = $request->query('city');
        $nearestStore = $this->findNearestStore($latitude, $longitude, $pincode, $city);
        $storeId = $nearestStore ? $nearestStore->id : null;
        $perPage = $request->query('per_page', 20);

        $query = Product::where('status', 'active')
            ->whereHas('brand', function($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            })
            ->with(['category', 'subCategory', 'brand', 'images' => function($q) {
                $q->where('is_primary', true);
            }]);

        // Store-based filtering
        if ($storeId) {
            $query->whereHas('variants', function($q) use ($storeId) {
                $q->whereHas('stocks', function($q2) use ($storeId) {
                    $q2->where('store_id', $storeId)
                       ->where('quantity', '>', 0);
                });
            });
        }

        $products = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Add variant and price information
        $products->getCollection()->transform(function($product) use ($storeId) {
            $product->load(['variants' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }]);

            foreach ($product->variants as $variant) {
                $currentPrice = $variant->prices()
                    ->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
                    })
                    ->orderBy('effective_from', 'desc')
                    ->first();

                $variant->current_price = $currentPrice;

                if ($storeId) {
                    $stock = ProductStock::where('product_variant_id', $variant->id)
                        ->where('store_id', $storeId)
                        ->first();
                    $variant->stock = $stock;
                }
            }

            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $products,
            'nearest_store' => $nearestStore ? [
                'id' => $nearestStore->id,
                'name' => $nearestStore->name,
                'distance' => round($nearestStore->distance, 2) . ' km',
            ] : null
        ], 200);
    }

    /**
     * Get top selling products
     */
    /**
     * Get top selling products
     * 
     * Query Parameters:
     * - limit: Number of products to return (default: 10)
     * - period: Time period - day, week, month, year (default: month)
     * - latitude: Customer latitude for nearest store
     * - longitude: Customer longitude for nearest store
     * - page: Page number for pagination (optional)
     * - per_page: Items per page (optional, if not provided uses limit)
     */
    public function topSelling(Request $request)
    {
        try {
            $latitude = $request->query('latitude');
            $longitude = $request->query('longitude');
            $pincode = $request->query('pincode');
            $city = $request->query('city');
            $nearestStore = $this->findNearestStore($latitude, $longitude, $pincode, $city);
            $storeId = $nearestStore ? $nearestStore->id : null;
            $limit = (int) $request->query('limit', 10);
            $period = $request->query('period', 'month'); // day, week, month, year, all
            $usePagination = $request->has('page') || $request->has('per_page');
            $perPage = $request->query('per_page', $limit);

            // Validate limit
            if ($limit < 1 || $limit > 100) {
                $limit = 10;
            }
            if ($perPage < 1 || $perPage > 100) {
                $perPage = $limit;
            }

            // Calculate date range
            $dateRange = match($period) {
                'day' => now()->subDay(),
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                'all' => null,
                default => now()->subMonth(),
            };

            // Get top selling products based on order items with sales count
            $topSellingQuery = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', '!=', 'cancelled')
                ->where('orders.payment_status', 'paid'); // Only count paid orders

            if ($dateRange) {
                $topSellingQuery->where('orders.created_at', '>=', $dateRange);
            }

            $topSellingData = $topSellingQuery
                ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('order_items.product_id')
                ->orderBy('total_sold', 'desc')
                ->limit($usePagination ? 1000 : $limit) // Get more if paginating
                ->get();

            if ($topSellingData->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No top selling products found',
                    'data' => $usePagination ? ['data' => [], 'current_page' => 1, 'per_page' => $perPage, 'total' => 0] : [],
                    'period' => $period,
                    'nearest_store' => $nearestStore ? [
                        'id' => $nearestStore->id,
                        'name' => $nearestStore->name,
                        'distance' => round($nearestStore->distance, 2) . ' km',
                    ] : null
                ], 200);
            }

            // Create map of product_id => total_sold
            $salesMap = $topSellingData->pluck('total_sold', 'product_id')->toArray();
            $topSellingProductIds = $topSellingData->pluck('product_id');

            $query = Product::whereIn('id', $topSellingProductIds)
                ->where('status', 'active')
                ->with(['category', 'subCategory', 'brand', 'images' => function($q) {
                    $q->where('is_primary', true);
                }]);

            // Store-based filtering
            if ($storeId) {
                $query->whereHas('variants', function($q) use ($storeId) {
                    $q->whereHas('stocks', function($q2) use ($storeId) {
                        $q2->where('store_id', $storeId)
                           ->where('quantity', '>', 0);
                    });
                });
            }

            // Get products
            $products = $query->get();

            // Maintain order from top selling query and add sales count
            $products = $products->map(function($product) use ($salesMap, $storeId) {
                // Add sales count
                $product->total_sold = $salesMap[$product->id] ?? 0;
                
                // Load variants
                $product->load(['variants' => function($q) {
                    $q->where('is_active', true)->orderBy('sort_order')->limit(1);
                }]);

                foreach ($product->variants as $variant) {
                    $currentPrice = $variant->prices()
                        ->where('is_active', true)
                        ->where(function($q) {
                            $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
                        })
                        ->where(function($q) {
                            $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
                        })
                        ->orderBy('effective_from', 'desc')
                        ->first();

                    $variant->current_price = $currentPrice;

                    if ($storeId) {
                        $stock = ProductStock::where('product_variant_id', $variant->id)
                            ->where('store_id', $storeId)
                            ->first();
                        $variant->stock = $stock;
                    }
                }

                return $product;
            })->sortByDesc('total_sold')->values();

            // Apply pagination if requested
            if ($usePagination) {
                $currentPage = (int) $request->query('page', 1);
                $total = $products->count();
                $offset = ($currentPage - 1) * $perPage;
                $paginatedProducts = $products->slice($offset, $perPage)->values();

                return response()->json([
                    'success' => true,
                    'message' => 'Top selling products retrieved successfully',
                    'data' => [
                        'data' => $paginatedProducts,
                        'current_page' => $currentPage,
                        'per_page' => $perPage,
                        'total' => $total,
                        'last_page' => ceil($total / $perPage),
                        'from' => $offset + 1,
                        'to' => min($offset + $perPage, $total),
                    ],
                    'period' => $period,
                    'nearest_store' => $nearestStore ? [
                        'id' => $nearestStore->id,
                        'name' => $nearestStore->name,
                        'distance' => round($nearestStore->distance, 2) . ' km',
                    ] : null
                ], 200);
            }

            // Return limited results without pagination
            $limitedProducts = $products->take($limit)->values();

            return response()->json([
                'success' => true,
                'message' => 'Top selling products retrieved successfully',
                'data' => $limitedProducts,
                'period' => $period,
                'nearest_store' => $nearestStore ? [
                    'id' => $nearestStore->id,
                    'name' => $nearestStore->name,
                    'distance' => round($nearestStore->distance, 2) . ' km',
                ] : null
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve top selling products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single product details
     */
    public function show(Request $request, $slug)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $pincode = $request->query('pincode');
        $city = $request->query('city');
        $nearestStore = $this->findNearestStore($latitude, $longitude, $pincode, $city);
        $storeId = $nearestStore ? $nearestStore->id : null;

        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category', 'subCategory', 'brand', 'images', 'attributes'])
            ->firstOrFail();

        // Load variants with prices and stocks
        $product->load(['variants' => function($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }]);

        foreach ($product->variants as $variant) {
            // Get current price
            $currentPrice = $variant->prices()
                ->where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
                })
                ->orderBy('effective_from', 'desc')
                ->first();

            $variant->current_price = $currentPrice;

            // Get stock for all stores or specific store
            if ($storeId) {
                $stock = ProductStock::where('product_variant_id', $variant->id)
                    ->where('store_id', $storeId)
                    ->first();
                $variant->stock = $stock;
            } else {
                $stocks = ProductStock::where('product_variant_id', $variant->id)
                    ->with('store')
                    ->get();
                $variant->stocks = $stocks;
            }

            // Load variant images
            $variant->load('images');
        }

        // Increment view count
        $product->increment('view_count');

        // Track recently viewed for authenticated customers
        if ($request->user()) {
            try {
                \App\Models\RecentlyViewedProduct::updateOrCreate(
                    [
                        'customer_id' => $request->user()->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'viewed_at' => now(),
                    ]
                );

                // Keep only last 50 recently viewed products per customer
                $count = \App\Models\RecentlyViewedProduct::where('customer_id', $request->user()->id)->count();
                if ($count > 50) {
                    $oldestRecords = \App\Models\RecentlyViewedProduct::where('customer_id', $request->user()->id)
                        ->orderBy('viewed_at', 'asc')
                        ->limit($count - 50)
                        ->pluck('id');
                    \App\Models\RecentlyViewedProduct::whereIn('id', $oldestRecords)->delete();
                }
            } catch (\Exception $e) {
                // Silently fail - don't break the product view if tracking fails
            }
        }

        return response()->json([
            'success' => true,
            'data' => $product,
            'nearest_store' => $nearestStore ? [
                'id' => $nearestStore->id,
                'name' => $nearestStore->name,
                'distance' => round($nearestStore->distance, 2) . ' km',
            ] : null
        ], 200);
    }
}