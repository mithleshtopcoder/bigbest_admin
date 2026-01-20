<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RatingReviewController extends Controller
{
    /**
     * Get all reviews for a product
     * 
     * GET /api/v1/products/{productId}/reviews
     * 
     * Query Parameters:
     * - page: Page number (default: 1)
     * - per_page: Items per page (default: 20)
     * - rating: Filter by rating (1-5)
     * - status: Filter by status (approved, pending, rejected) - default: approved
     * - sort_by: Sort by (latest, oldest, rating_high, rating_low) - default: latest
     */
    public function index(Request $request, $productId)
    {
        try {
            $product = Product::find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $perPage = $request->get('per_page', 20);
            $rating = $request->get('rating');
            $status = $request->get('status', 'approved');
            $sortBy = $request->get('sort_by', 'latest');

            $query = ProductReview::where('product_id', $productId)
                ->with([
                    'customer:id,first_name,last_name,photo',
                    'order:id,order_number'
                ]);

            // Filter by status (default: approved for public, but allow admin to see all)
            if ($status) {
                $query->where('status', $status);
            }

            // Filter by rating
            if ($rating && in_array($rating, [1, 2, 3, 4, 5])) {
                $query->where('rating', $rating);
            }

            // Sorting
            switch ($sortBy) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'rating_high':
                    $query->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
                    break;
                case 'rating_low':
                    $query->orderBy('rating', 'asc')->orderBy('created_at', 'desc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $reviews = $query->paginate($perPage);

            // Transform data for response
            $reviews->getCollection()->transform(function($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'status' => $review->status,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'customer' => [
                        'id' => $review->customer->id,
                        'name' => $review->customer->full_name,
                        'photo' => $review->customer->photo ? url('storage/' . $review->customer->photo) : null,
                    ],
                    'order' => $review->order ? [
                        'id' => $review->order->id,
                        'order_number' => $review->order->order_number,
                    ] : null,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $review->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Reviews retrieved successfully',
                'data' => $reviews,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get review statistics for a product
     * 
     * GET /api/v1/products/{productId}/reviews/statistics
     */
    public function statistics(Request $request, $productId)
    {
        try {
            $product = Product::find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $reviews = ProductReview::where('product_id', $productId)
                ->where('status', 'approved')
                ->selectRaw('
                    COUNT(*) as total_reviews,
                    AVG(rating) as average_rating,
                    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
                    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1
                ')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Review statistics retrieved successfully',
                'data' => [
                    'total_reviews' => (int) $reviews->total_reviews,
                    'average_rating' => round((float) $reviews->average_rating, 2),
                    'rating_distribution' => [
                        '5' => (int) $reviews->rating_5,
                        '4' => (int) $reviews->rating_4,
                        '3' => (int) $reviews->rating_3,
                        '2' => (int) $reviews->rating_2,
                        '1' => (int) $reviews->rating_1,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve review statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new review
     * 
     * POST /api/v1/products/{productId}/reviews
     * 
     * Request Body (JSON):
     * {
     *     "rating": 5,
     *     "review": "Great product!",
     *     "order_id": 123 (optional)
     * }
     */
    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
            'order_id' => 'nullable|exists:orders,id',
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
            $product = Product::find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Check if customer already reviewed this product
            $existingReview = ProductReview::where('product_id', $productId)
                ->where('customer_id', $customer->id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this product. You can update your existing review.',
                ], 400);
            }

            // Verify order if provided
            $isVerifiedPurchase = false;
            if ($request->order_id) {
                $order = Order::where('id', $request->order_id)
                    ->where('customer_id', $customer->id)
                    ->where('status', 'delivered')
                    ->first();

                if ($order) {
                    // Check if order contains this product
                    $orderItem = OrderItem::where('order_id', $order->id)
                        ->where('product_id', $productId)
                        ->first();

                    if ($orderItem) {
                        $isVerifiedPurchase = true;
                    }
                }
            }

            // Create review
            $review = ProductReview::create([
                'product_id' => $productId,
                'customer_id' => $customer->id,
                'order_id' => $request->order_id,
                'rating' => $request->rating,
                'review' => $request->review,
                'status' => 'pending', // Default to pending for moderation
                'is_verified_purchase' => $isVerifiedPurchase,
            ]);

            // Update product rating and total reviews (only if approved)
            // Note: This will be updated when admin approves the review
            // For now, we'll update it immediately, but in production you might want to do this on approval

            $review->load([
                'customer:id,first_name,last_name,photo',
                'order:id,order_number'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully. It will be visible after approval.',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'status' => $review->status,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'customer' => [
                        'id' => $review->customer->id,
                        'name' => $review->customer->full_name,
                        'photo' => $review->customer->photo ? url('storage/' . $review->customer->photo) : null,
                    ],
                    'order' => $review->order ? [
                        'id' => $review->order->id,
                        'order_number' => $review->order->order_number,
                    ] : null,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing review
     * 
     * PUT /api/v1/reviews/{reviewId}
     * 
     * Request Body (JSON):
     * {
     *     "rating": 4,
     *     "review": "Updated review text"
     * }
     */
    public function update(Request $request, $reviewId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
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

            $review = ProductReview::where('id', $reviewId)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you do not have permission to update it'
                ], 404);
            }

            // Update review
            $review->update([
                'rating' => $request->has('rating') ? $request->rating : $review->rating,
                'review' => $request->has('review') ? $request->review : $review->review,
                'status' => 'pending', // Reset to pending when updated
            ]);

            $review->load([
                'customer:id,first_name,last_name,photo',
                'order:id,order_number',
                'product:id,name,slug'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully. It will be visible after approval.',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'status' => $review->status,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'customer' => [
                        'id' => $review->customer->id,
                        'name' => $review->customer->full_name,
                        'photo' => $review->customer->photo ? url('storage/' . $review->customer->photo) : null,
                    ],
                    'product' => [
                        'id' => $review->product->id,
                        'name' => $review->product->name,
                        'slug' => $review->product->slug,
                    ],
                    'updated_at' => $review->updated_at->format('Y-m-d H:i:s'),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific review
     * 
     * GET /api/v1/reviews/{reviewId}
     */
    public function show(Request $request, $reviewId)
    {
        try {
            $review = ProductReview::with([
                'customer:id,first_name,last_name,photo',
                'order:id,order_number',
                'product:id,name,slug,thumbnail_image'
            ])->find($reviewId);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Review retrieved successfully',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'status' => $review->status,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'customer' => [
                        'id' => $review->customer->id,
                        'name' => $review->customer->full_name,
                        'photo' => $review->customer->photo ? url('storage/' . $review->customer->photo) : null,
                    ],
                    'product' => [
                        'id' => $review->product->id,
                        'name' => $review->product->name,
                        'slug' => $review->product->slug,
                        'thumbnail_image' => $review->product->thumbnail_image,
                    ],
                    'order' => $review->order ? [
                        'id' => $review->order->id,
                        'order_number' => $review->order->order_number,
                    ] : null,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $review->updated_at->format('Y-m-d H:i:s'),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a review
     * 
     * DELETE /api/v1/reviews/{reviewId}
     */
    public function destroy(Request $request, $reviewId)
    {
        try {
            $customer = $request->user();

            $review = ProductReview::where('id', $reviewId)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you do not have permission to delete it'
                ], 404);
            }

            $productId = $review->product_id;
            $review->delete();

            // Update product rating and total reviews
            self::updateProductRating($productId);

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get customer's reviews
     * 
     * GET /api/v1/customer/reviews
     * 
     * Query Parameters:
     * - page: Page number (default: 1)
     * - per_page: Items per page (default: 20)
     * - status: Filter by status (approved, pending, rejected)
     */
    public function myReviews(Request $request)
    {
        try {
            $customer = $request->user();

            $perPage = $request->get('per_page', 20);
            $status = $request->get('status');

            $query = ProductReview::where('customer_id', $customer->id)
                ->with([
                    'product:id,name,slug,thumbnail_image',
                    'order:id,order_number'
                ]);

            if ($status) {
                $query->where('status', $status);
            }

            $reviews = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // Transform data for response
            $reviews->getCollection()->transform(function($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'status' => $review->status,
                    'is_verified_purchase' => $review->is_verified_purchase,
                    'product' => [
                        'id' => $review->product->id,
                        'name' => $review->product->name,
                        'slug' => $review->product->slug,
                        'thumbnail_image' => $review->product->thumbnail_image,
                    ],
                    'order' => $review->order ? [
                        'id' => $review->order->id,
                        'order_number' => $review->order->order_number,
                    ] : null,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $review->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Your reviews retrieved successfully',
                'data' => $reviews,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if customer can review a product
     * 
     * GET /api/v1/products/{productId}/reviews/check
     */
    public function check(Request $request, $productId)
    {
        try {
            $customer = $request->user();
            $product = Product::find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Check if customer already reviewed
            $existingReview = ProductReview::where('product_id', $productId)
                ->where('customer_id', $customer->id)
                ->first();

            // Check if customer has purchased this product
            $hasPurchased = OrderItem::whereHas('order', function($q) use ($customer) {
                $q->where('customer_id', $customer->id)
                  ->where('status', 'delivered');
            })
            ->where('product_id', $productId)
            ->exists();

            return response()->json([
                'success' => true,
                'data' => [
                    'can_review' => !$existingReview,
                    'has_reviewed' => $existingReview ? true : false,
                    'existing_review_id' => $existingReview ? $existingReview->id : null,
                    'has_purchased' => $hasPurchased,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check review status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update product rating and total reviews
     * This should be called when a review is approved/rejected/deleted
     */
    public static function updateProductRating($productId)
    {
        $approvedReviews = ProductReview::where('product_id', $productId)
            ->where('status', 'approved')
            ->get();

        $totalReviews = $approvedReviews->count();
        $averageRating = $totalReviews > 0 
            ? round($approvedReviews->avg('rating'), 2) 
            : 0.00;

        Product::where('id', $productId)->update([
            'rating' => $averageRating,
            'total_reviews' => $totalReviews,
        ]);
    }
}
