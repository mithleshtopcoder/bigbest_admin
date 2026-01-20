<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\SocialMediaController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\ComboOfferController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\PolicyController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\RecentlyViewedController;
use App\Http\Controllers\Api\RatingReviewController;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Public Customer Routes
Route::prefix('v1')->group(function () {
    Broadcast::routes();
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/verify-phone', [AuthController::class, 'verifyPhone']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
    
   
    // App Settings APIs
    Route::get('/settings', [SettingController::class, 'index']); // Get all settings
    Route::get('/settings/company', [SettingController::class, 'companyInfo']); // Get company info (logo, name, etc.)
    Route::get('/settings/social-media', [SettingController::class, 'socialMedia']); // Get social media links
    Route::get('/settings/delivery', [SettingController::class, 'deliverySettings']); // Get delivery settings
    Route::get('/settings/maintenance', [SettingController::class, 'maintenanceMode']); // Check maintenance mode
    Route::get('/settings/{key}', [SettingController::class, 'show']); // Get specific setting by key

    Route::get('/banners/{type?}', [BannerController::class, 'index']);  // {type?} =>  'home', 'category', 'product', 'promotional', 'other'
    Route::get('/social-media', [SocialMediaController::class, 'index']);
    Route::get('/support/faqs', [SupportController::class, 'faqs']);
    
    // Policies APIs
    Route::get('/policies', [PolicyController::class, 'index']); // Get all policies
    Route::get('/policies/{type}', [PolicyController::class, 'show']); // Get policy by type (terms_conditions, privacy_policy, etc.)
    
    // Categories APIs
    Route::get('/categories', [CategoryController::class, 'index']); // Get all categories (Query: page, per_page, search, status)
    Route::get('/categories/{categorySlug}/sub-categories', [CategoryController::class, 'subCategories']); // Get sub-categories by parent category slug
    Route::get('/categories/{slug}', [CategoryController::class, 'show']); // Get category details by slug
    Route::get('/sub-categories/{slug}', [CategoryController::class, 'showSubCategory']); // Get sub-category details by slug
    Route::get('/categories/{categorySlug}/{subCategorySlug}', [CategoryController::class, 'categorySubCategory']);

    // Brands APIs
    Route::get('/brands', [BrandController::class, 'index']); // Get all brands (Query: page, per_page, search, status)
    Route::get('/brands/{slug}', [BrandController::class, 'show']); // Get brand details by slug
    
    // Products APIs
    Route::get('/products', [ProductController::class, 'index']); // Get all products (Query: page, per_page, search, category, brand, min_price, max_price, sort_by, order, status)
    Route::get('/products/featured', [ProductController::class, 'featured']); // Get featured products (Query: page, per_page)
    Route::get('/products/top-selling', [ProductController::class, 'topSelling']); // Get top-selling products sorted by sales (Query: limit, period, latitude, longitude, page, per_page)
    Route::get('/products/category/{categorySlug}', [ProductController::class, 'byCategory']); // Get products by category slug (Query: page, per_page, search, min_price, max_price, sort_by, order)
    Route::get('/products/brand/{brandSlug}', [ProductController::class, 'byBrand']); // Get products by brand slug (Query: page, per_page, search, min_price, max_price, sort_by, order)
    Route::get('/products/{slug}', [ProductController::class, 'show']); // Get product details by slug (includes variants, pricing, stock, reviews, related products, .... etc)
    
    // Product Reviews APIs (Public - only approved reviews)
    Route::get('/products/{productId}/reviews', [RatingReviewController::class, 'index']); // Get all reviews for a product (Query: page, per_page, rating, status, sort_by)
    Route::get('/products/{productId}/reviews/statistics', [RatingReviewController::class, 'statistics']); // Get review statistics for a product
    Route::get('/reviews/{reviewId}', [RatingReviewController::class, 'show']); // Get a specific review
    
    // Coupons APIs
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);
    
    // Offers APIs
    Route::get('/offers', [OfferController::class, 'index']);
    Route::get('/offers/featured', [OfferController::class, 'featured']);
    Route::get('/offers/applicable', [OfferController::class, 'applicable']);
    Route::get('/offers/{slug}', [OfferController::class, 'show']);
    
    // Combo Offers APIs
    Route::get('/combo-offers', [ComboOfferController::class, 'index']);
    Route::get('/combo-offers/featured', [ComboOfferController::class, 'featured']);
    Route::get('/combo-offers/{slug}', [ComboOfferController::class, 'show']);
    
    // Protected Customer Routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('customer')->group(function () {
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/device-token', [AuthController::class, 'updateDeviceToken']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
            Route::post('/photo', [AuthController::class, 'updatePhoto']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/addresses', [AuthController::class, 'addresses']);
            Route::post('/addresses', [AuthController::class, 'addAddress']);
            Route::put('/addresses/{id}', [AuthController::class, 'updateAddress']);
            Route::delete('/addresses/{id}', [AuthController::class, 'deleteAddress']);
            Route::get('/wallet', [AuthController::class, 'wallet']);
            Route::get('/loyalty-points', [AuthController::class, 'loyaltyPoints']);
        });

        // Cart APIs
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index']);
            Route::post('/', [CartController::class, 'store']);
            Route::post('/combo', [CartController::class, 'addCombo']); // Add combo offer to cart
            Route::put('/{id}', [CartController::class, 'update']);
            Route::delete('/{id}', [CartController::class, 'destroy']);
            Route::delete('/', [CartController::class, 'clear']);
        });

        // Order APIs
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'store']); // Checkout
            Route::get('/{id}', [OrderController::class, 'show']);
            Route::get('/invoice/{id}/url', [OrderController::class, 'invoice']);
            Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
        });

        // Payment APIs
        Route::prefix('payments')->group(function () {
            Route::post('/', [PaymentController::class, 'store']); // Process payment
            Route::get('/{id}', [PaymentController::class, 'show']);
            Route::post('/callback', [PaymentController::class, 'callback']); // Payment gateway callback
        });
        
        Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('api.payment.webhook');


        // Coupon APIs (Protected)
        Route::prefix('coupons')->group(function () {
            Route::post('/apply', [CouponController::class, 'apply']); // Apply coupon during checkout
            Route::get('/my-coupons', [CouponController::class, 'myCoupons']); // Customer's coupon history
        });

        // Notification APIs
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
            Route::get('/{id}', [NotificationController::class, 'show']);
            Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
            Route::delete('/{id}', [NotificationController::class, 'destroy']);
            Route::delete('/read/all', [NotificationController::class, 'deleteAllRead']);
        });

        // Wishlist APIs
        Route::prefix('wishlist')->group(function () {
            Route::get('/', [WishlistController::class, 'index']);
            Route::post('/', [WishlistController::class, 'store']);
            Route::get('/check/{product_id}', [WishlistController::class, 'check']);
            Route::delete('/product/{product_id}', [WishlistController::class, 'removeByProduct']);
            Route::delete('/{id}', [WishlistController::class, 'destroy']);
            Route::delete('/', [WishlistController::class, 'clear']);
        });

        // Recently Viewed APIs
        Route::prefix('recently-viewed')->group(function () {
            Route::get('/', [RecentlyViewedController::class, 'index']);
            Route::post('/', [RecentlyViewedController::class, 'store']);
            Route::delete('/{id}', [RecentlyViewedController::class, 'destroy']);
            Route::delete('/', [RecentlyViewedController::class, 'clear']);
        });

        // Support Ticket APIs
        Route::prefix('support-tickets')->group(function () {
            Route::get('/', [SupportController::class, 'tickets']); // List customer's tickets (with status filter)
            Route::post('/', [SupportController::class, 'createTicket']); // Create new ticket
        });

        // Rating & Review APIs (Protected)
        Route::prefix('products')->group(function () {
            Route::post('/{productId}/reviews', [RatingReviewController::class, 'store']); // Create a new review
            Route::get('/{productId}/reviews/check', [RatingReviewController::class, 'check']); // Check if customer can review
        });
        
        Route::prefix('reviews')->group(function () {
            Route::get('/my-reviews', [RatingReviewController::class, 'myReviews']); // Get customer's reviews (Query: page, per_page, status)
            Route::put('/{reviewId}', [RatingReviewController::class, 'update']); // Update a review
            Route::delete('/{reviewId}', [RatingReviewController::class, 'destroy']); // Delete a review
        });

        
    });

    
});