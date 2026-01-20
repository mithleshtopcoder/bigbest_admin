<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vegetablesCategory = Category::where('slug', 'vegetables')->first();
        $fruitsCategory = Category::where('slug', 'fruits')->first();
        $leafySubCat = SubCategory::where('slug', 'leafy-vegetables')->first();
        $organicValley = Brand::where('slug', 'organic-valley')->first();
        $spinach = Product::where('slug', 'fresh-organic-spinach')->first();

        $offers = [
            [
                'title' => 'Buy 2 Get 1 Free - Leafy Vegetables',
                'slug' => 'buy-2-get-1-free-leafy-vegetables',
                'description' => 'Buy 2 packs of leafy vegetables and get 1 free!',
                'offer_type' => 'sub_category',
                'discount_type' => 'buy_x_get_y',
                'buy_quantity' => 2,
                'get_quantity' => 1,
                'minimum_order_amount' => 200.00,
                'sub_category_id' => $leafySubCat->id,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'priority' => 10,
                'is_active' => true,
                'is_featured' => true,
                'terms_conditions' => 'Valid on leafy vegetables only. Minimum order ₹200.',
            ],
            [
                'title' => '20% Off on All Vegetables',
                'slug' => '20-percent-off-all-vegetables',
                'description' => 'Get 20% discount on all vegetables category products.',
                'offer_type' => 'category',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'minimum_order_amount' => 300.00,
                'maximum_discount_amount' => 500.00,
                'category_id' => $vegetablesCategory->id,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'priority' => 8,
                'is_active' => true,
                'is_featured' => true,
                'terms_conditions' => 'Valid on vegetables category. Minimum order ₹300.',
            ],
            [
                'title' => '15% Off on Fresh Fruits',
                'slug' => '15-percent-off-fresh-fruits',
                'description' => 'Enjoy 15% discount on all fresh organic fruits.',
                'offer_type' => 'category',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'minimum_order_amount' => 400.00,
                'maximum_discount_amount' => 400.00,
                'category_id' => $fruitsCategory->id,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'priority' => 7,
                'is_active' => true,
                'is_featured' => false,
                'terms_conditions' => 'Valid on fruits category. Minimum order ₹400.',
            ],
            [
                'title' => 'Cart Total - 10% Off Above ₹1000',
                'slug' => 'cart-total-10-percent-off',
                'description' => 'Get 10% off on cart total when you shop above ₹1000.',
                'offer_type' => 'cart',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'minimum_order_amount' => 1000.00,
                'maximum_discount_amount' => 500.00,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(2),
                'priority' => 5,
                'is_active' => true,
                'is_featured' => true,
                'terms_conditions' => 'Valid on cart total above ₹1000. Maximum discount ₹500.',
            ],
            [
                'title' => 'Organic Valley Brand - 25% Off',
                'slug' => 'organic-valley-25-percent-off',
                'description' => 'Special discount on all Organic Valley brand products.',
                'offer_type' => 'brand',
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'minimum_order_amount' => 500.00,
                'maximum_discount_amount' => 750.00,
                'brand_id' => $organicValley->id,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'priority' => 9,
                'is_active' => true,
                'is_featured' => true,
                'terms_conditions' => 'Valid on Organic Valley brand products. Minimum order ₹500.',
            ],
            [
                'title' => 'Flat ₹50 Off on Spinach',
                'slug' => 'flat-50-off-spinach',
                'description' => 'Get flat ₹50 discount on fresh organic spinach.',
                'offer_type' => 'product',
                'discount_type' => 'fixed',
                'discount_value' => 50.00,
                'minimum_order_amount' => 200.00,
                'product_id' => $spinach->id ?? null,
                'valid_from' => now(),
                'valid_to' => now()->addWeeks(2),
                'priority' => 6,
                'is_active' => true,
                'is_featured' => false,
                'terms_conditions' => 'Valid on spinach products only. Minimum order ₹200.',
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}
