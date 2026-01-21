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
    // Filters
    $categoryId = $request->query('category_id');
    $subCategoryId = $request->query('sub_category_id');
    $brandId = $request->query('brand_id');
    $isOrganic = $request->query('is_organic');
    $search = $request->query('search');
    $sortBy = $request->query('sort_by', 'latest');
    $perPage = $request->query('per_page', 20);

    $query = Product::where('status', 'active')
        ->where('approval_status', 'approved')
        ->with([
            'images' => fn($q) => $q->orderBy('sort_order')->limit(1),
            'variants' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')->limit(1)
        ]);

    // Apply filters
    if ($categoryId) $query->where('category_id', $categoryId);
    if ($subCategoryId) $query->where('sub_category_id', $subCategoryId);
    if ($brandId) $query->where('brand_id', $brandId);
    if ($isOrganic !== null) $query->where('is_organic', filter_var($isOrganic, FILTER_VALIDATE_BOOLEAN));

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%");
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
        default:
            $query->orderBy('created_at', 'desc');
            break;
    }

    $products = $query->paginate($perPage);

    // Fetch ratings for all products in one query
    $productIds = $products->pluck('id')->toArray();
    $ratingsData = ProductReview::whereIn('product_id', $productIds)
        ->where('status', 'approved')
        ->selectRaw('product_id, COUNT(*) as total_reviews, AVG(rating) as average_rating')
        ->groupBy('product_id')
        ->get()
        ->keyBy('product_id');

    // Transform products for API
    $products->getCollection()->transform(function($product) use ($ratingsData) {
        $primaryImage = $product->images->first();
        $imagePath = $primaryImage ? $primaryImage->image_path : $product->thumbnail_image;

        $variant = $product->variants->first();
        $currentPrice = null;
        $stockQuantity = 0;
        $unit = null;
        $discountPercentage = 0;

        if ($variant) {
            // Current price for this variant
            $currentPrice = $variant->prices()
                ->where('is_active', true)
                ->where(fn($q) => $q->whereNull('effective_from')->orWhere('effective_from', '<=', now()))
                ->where(fn($q) => $q->whereNull('effective_to')->orWhere('effective_to', '>=', now()))
                ->orderBy('effective_from', 'desc')
                ->first();

            // Total stock across all stores
            $stockQuantity = $variant->stocks()->sum('quantity');

            $unit = $variant->unit;
            if ($currentPrice?->compare_at_price > $currentPrice?->price) {
                $discountPercentage = round((($currentPrice->compare_at_price - $currentPrice->price) / $currentPrice->compare_at_price) * 100, 0);
            }
        }

        $ratingInfo = $ratingsData[$product->id] ?? null;
        $totalReviews = $ratingInfo?->total_reviews ?? $product->total_reviews ?? 0;
        $rating = $ratingInfo?->average_rating !== null ? round((float)$ratingInfo->average_rating, 2) : ($product->rating ?? 0.0);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $imagePath,
            'rating' => $rating,
            'total_reviews' => $totalReviews,
            'price' => $currentPrice?->price,
            'compare_at_price' => $currentPrice?->compare_at_price,
            'discount_percentage' => $discountPercentage,
            'unit' => $unit,
            'quantity' => $stockQuantity,
            'is_organic' => $product->is_organic,
            'vendor_id' => $product->vendor_id,
            'variant' => $variant ? [
                'id' => $variant->id,
                'name' => $variant->name,
                'unit' => $variant->unit,
                'unit_value' => $variant->unit_value,
                'is_default' => $variant->is_default,
                'is_active' => $variant->is_active,
                'sort_order' => $variant->sort_order,
                'price' => $currentPrice?->price,
                'compare_at_price' => $currentPrice?->compare_at_price,
                'discount_percentage' => $discountPercentage,
                'stock_quantity' => $stockQuantity,
            ] : null,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $products,
    ], 200);
}



    /**
     * Get featured products
     */
   public function featured(Request $request)
{
    $limit = $request->query('limit', 10);

    $query = Product::where('status', 'active')
        ->where('is_featured', true)
        ->where('approval_status', 'approved') // Only approved products
        ->with([
            'category',
            'subCategory',
            'brand',
            'images' => fn($q) => $q->orderBy('sort_order')->limit(1),
            'variants' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')
        ]);

    $products = $query->orderBy('sort_order')
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();

    // Fetch ratings for all products in one query
    $productIds = $products->pluck('id')->toArray();
    $ratingsData = ProductReview::whereIn('product_id', $productIds)
        ->where('status', 'approved')
        ->selectRaw('product_id, COUNT(*) as total_reviews, AVG(rating) as average_rating')
        ->groupBy('product_id')
        ->get()
        ->keyBy('product_id');

    // Transform products for API
    $products->transform(function ($product) use ($ratingsData) {
        $primaryImage = $product->images->first();
        $imagePath = $primaryImage ? $primaryImage->image_path : $product->thumbnail_image;

        $ratingInfo = $ratingsData[$product->id] ?? null;
        $totalReviews = $ratingInfo?->total_reviews ?? $product->total_reviews ?? 0;
        $rating = $ratingInfo?->average_rating !== null ? round((float)$ratingInfo->average_rating, 2) : ($product->rating ?? 0.0);

        // Transform variants
        $variants = $product->variants->map(function ($variant) {
            $currentPrice = $variant->prices()
                ->where('is_active', true)
                ->where(fn($q) => $q->whereNull('effective_from')->orWhere('effective_from', '<=', now()))
                ->where(fn($q) => $q->whereNull('effective_to')->orWhere('effective_to', '>=', now()))
                ->orderBy('effective_from', 'desc')
                ->first();

            // Total stock across all stores
            $stockQuantity = $variant->stocks()->sum('quantity');

            $discountPercentage = 0;
            if ($currentPrice?->compare_at_price > $currentPrice?->price) {
                $discountPercentage = round((($currentPrice->compare_at_price - $currentPrice->price) / $currentPrice->compare_at_price) * 100, 0);
            }

            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'unit' => $variant->unit,
                'unit_value' => $variant->unit_value,
                'is_default' => $variant->is_default,
                'is_active' => $variant->is_active,
                'sort_order' => $variant->sort_order,
                'price' => $currentPrice?->price,
                'compare_at_price' => $currentPrice?->compare_at_price,
                'discount_percentage' => $discountPercentage,
                'stock_quantity' => $stockQuantity,
            ];
        });

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $imagePath,
            'vendor_id' => $product->vendor_id,
            'category' => $product->category?->name,
            'sub_category' => $product->subCategory?->name,
            'brand' => $product->brand?->name,
            'is_featured' => $product->is_featured,
            'rating' => $rating,
            'total_reviews' => $totalReviews,
            'variants' => $variants,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $products
    ], 200);
}



    /**
     * Get products by category
     */
    public function byCategory(Request $request, $categorySlug)
{
    $perPage = $request->query('per_page', 20);

    // Fetch products by category
    $query = Product::where('status', 'active')
        ->where('approval_status', 'approved') // Only approved products
        ->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        })
        ->with([
            'category:id,name,slug',
            'subCategory:id,name,slug',
            'brand:id,name',
            'images' => fn($q) => $q->where('is_primary', true)->select('id','product_id','image_path'),
            'variants' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')->with([
                'prices' => fn($p) => $p->where('is_active', true)
                                         ->where(fn($q) => $q->whereNull('effective_from')->orWhere('effective_from', '<=', now()))
                                         ->where(fn($q) => $q->whereNull('effective_to')->orWhere('effective_to', '>=', now()))
                                         ->orderBy('effective_from', 'desc')
                                         ->select('id','product_variant_id','price','compare_at_price'),
                'stocks' => fn($s) => $s->select('id','product_variant_id','quantity')
            ])
        ]);

    $products = $query->orderBy('sort_order')
                      ->orderBy('created_at', 'desc')
                      ->paginate($perPage);

    // Transform products for API
    $products->getCollection()->transform(function ($product) {

        $primaryImage = $product->images->first();
        $imagePath = $primaryImage ? $primaryImage->image_path : $product->thumbnail_image;

        $variantsData = $product->variants->map(function ($variant) {
            $currentPrice = $variant->prices->first();
            $stockQuantity = $variant->stocks->sum('quantity');

            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'unit' => $variant->unit,
                'unit_value' => $variant->unit_value,
                'current_price' => $currentPrice?->price,
                'compare_at_price' => $currentPrice?->compare_at_price,
                'stock_quantity' => $stockQuantity,
                'is_default' => $variant->is_default,
                'is_active' => $variant->is_active,
                'sort_order' => $variant->sort_order,
            ];
        });

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $imagePath,
            'vendor_id' => $product->vendor_id,
            'category' => $product->category?->name,
            'sub_category' => $product->subCategory?->name,
            'brand' => $product->brand?->name,
            'variants' => $variantsData,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $products,
    ], 200);
}


    /**
     * Get products by brand
     */
  public function byBrand(Request $request, $brandSlug)
{
    $perPage = $request->query('per_page', 20);

    // Query products by brand slug
    $query = Product::where('status', 'active')
        ->where('approval_status', 'approved')
        ->whereHas('brand', fn($q) => $q->where('slug', $brandSlug))
        ->with([
            'category',
            'subCategory',
            'brand',
            'images' => fn($q) => $q->where('is_primary', true),
            'variants' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ]);

    $products = $query->orderBy('sort_order')
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

    // Transform products to include variant details, price & stock
    $products->getCollection()->transform(function ($product) {

        $variantsData = $product->variants->map(function ($variant) {

            // Fetch the latest active price for the variant
            $currentPrice = $variant->prices()
                ->where('is_active', true)
                ->where(fn($q) => $q->whereNull('effective_from')->orWhere('effective_from', '<=', now()))
                ->where(fn($q) => $q->whereNull('effective_to')->orWhere('effective_to', '>=', now()))
                ->orderBy('effective_from', 'desc')
                ->first();

            // Sum of all stock quantities for this variant across all stores
            $stockQuantity = $variant->stocks()->sum('quantity');

            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'unit' => $variant->unit,
                'unit_value' => $variant->unit_value,
                'current_price' => $currentPrice?->price,
                'compare_at_price' => $currentPrice?->compare_at_price,
                'stock_quantity' => $stockQuantity,
            ];
        });

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $product->images->first()?->image_path ?? $product->thumbnail_image,
            'vendor_id' => $product->vendor_id,
            'category' => $product->category?->name,
            'sub_category' => $product->subCategory?->name,
            'brand' => $product->brand?->name,
            'variants' => $variantsData,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $products,
    ], 200);
}


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
        $limit = ($limit < 1 || $limit > 100) ? 10 : $limit;
        $perPage = ($perPage < 1 || $perPage > 100) ? $limit : $perPage;

        // Calculate date range
        $dateRange = match($period) {
            'day' => now()->subDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            'all' => null,
            default => now()->subMonth(),
        };

        // Get top selling products based on order items
        $topSellingQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.payment_status', 'paid');

        if ($dateRange) {
            $topSellingQuery->where('orders.created_at', '>=', $dateRange);
        }

        $topSellingData = $topSellingQuery
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($usePagination ? 1000 : $limit)
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

        // Map product_id => total_sold
        $salesMap = $topSellingData->pluck('total_sold', 'product_id')->toArray();
        $topSellingProductIds = $topSellingData->pluck('product_id');

        $query = Product::whereIn('id', $topSellingProductIds)
            ->where('status', 'active')
            ->where('approval_status', 'approved') // Only approved products
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

        $products = $query->get();

        // Transform products
        $products = $products->map(function($product) use ($salesMap, $storeId) {
            $product->total_sold = $salesMap[$product->id] ?? 0;

            $product->load(['variants' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order')->limit(1);
            }]);

            $variantsData = $product->variants->map(function ($variant) use ($storeId) {
                $currentPrice = $variant->prices()
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
                    })
                    ->orderBy('effective_from', 'desc')
                    ->first();

                $stockQuantity = 0;
                if ($storeId) {
                    $stock = ProductStock::where('product_variant_id', $variant->id)
                        ->where('store_id', $storeId)
                        ->first();
                    $stockQuantity = $stock ? $stock->quantity : 0;
                }

                return [
                    'id' => $variant->id,
                    'unit' => $variant->unit,
                    'current_price' => $currentPrice?->price,
                    'compare_at_price' => $currentPrice?->compare_at_price,
                    'stock_quantity' => $stockQuantity,
                ];
            });

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->images->first()?->image_path ?? $product->thumbnail_image,
                'vendor_id' => $product->vendor_id,
                'category' => $product->category?->name,
                'sub_category' => $product->subCategory?->name,
                'brand' => $product->brand?->name,
                'variants' => $variantsData,
                'total_sold' => $product->total_sold,
            ];
        })->sortByDesc('total_sold')->values();

        // Pagination
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

        // Return limited without pagination
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

    // Fetch only approved product
    $product = Product::where('slug', $slug)
        ->where('status', 'active')
        ->where('approval_status', 'approved') // Only approved products
        ->with(['category', 'subCategory', 'brand', 'images', 'attributes'])
        ->firstOrFail();

    // Load variants with prices and stocks
    $product->load(['variants' => function($q) {
        $q->where('is_active', true)->orderBy('sort_order');
    }]);

    $variantsData = $product->variants->map(function ($variant) use ($storeId) {
        // Get current price
        $currentPrice = $variant->prices()
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('effective_from')->orWhere('effective_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('effective_to')->orWhere('effective_to', '>=', now());
            })
            ->orderBy('effective_from', 'desc')
            ->first();

        // Get stock per store or all stores
        $stockData = null;
        if ($storeId) {
            $stock = ProductStock::where('product_variant_id', $variant->id)
                ->where('store_id', $storeId)
                ->first();
            $stockData = $stock ? $stock->quantity : 0;
        } else {
            $stocks = ProductStock::where('product_variant_id', $variant->id)
                ->with('store')
                ->get()
                ->map(function($s) {
                    return [
                        'store_id' => $s->store_id,
                        'store_name' => $s->store->name ?? null,
                        'quantity' => $s->quantity
                    ];
                });
            $stockData = $stocks;
        }

        return [
            'id' => $variant->id,
            'unit' => $variant->unit,
            'current_price' => $currentPrice?->price,
            'compare_at_price' => $currentPrice?->compare_at_price,
            'stock' => $stockData,
            'images' => $variant->images
        ];
    });

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
                ['viewed_at' => now()]
            );

            $count = \App\Models\RecentlyViewedProduct::where('customer_id', $request->user()->id)->count();
            if ($count > 50) {
                $oldestRecords = \App\Models\RecentlyViewedProduct::where('customer_id', $request->user()->id)
                    ->orderBy('viewed_at', 'asc')
                    ->limit($count - 50)
                    ->pluck('id');
                \App\Models\RecentlyViewedProduct::whereIn('id', $oldestRecords)->delete();
            }
        } catch (\Exception $e) {
            // Silently fail
        }
    }

    // Prepare final response
    $responseData = [
        'id' => $product->id,
        'name' => $product->name,
        'slug' => $product->slug,
        'vendor_id' => $product->vendor_id,
        'category' => $product->category?->name,
        'sub_category' => $product->subCategory?->name,
        'brand' => $product->brand?->name,
        'thumbnail_image' => $product->thumbnail_image,
        'attributes' => $product->attributes,
        'variants' => $variantsData,
        'view_count' => $product->view_count,
    ];

    return response()->json([
        'success' => true,
        'data' => $responseData,
        'nearest_store' => $nearestStore ? [
            'id' => $nearestStore->id,
            'name' => $nearestStore->name,
            'distance' => round($nearestStore->distance, 2) . ' km',
        ] : null
    ], 200);
}

}