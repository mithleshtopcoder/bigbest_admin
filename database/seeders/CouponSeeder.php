<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vegetablesCategory = Category::where('slug', 'vegetables')->first();
        $fruitsCategory = Category::where('slug', 'fruits')->first();
        $organicValley = Brand::where('slug', 'organic-valley')->first();

        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Offer - 10% Off',
                'description' => 'Get 10% off on your first order. Valid for new customers only.',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'minimum_order_amount' => 500.00,
                'maximum_discount_amount' => 200.00,
                'applicable_to' => 'all',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(3),
                'usage_limit' => 1000,
                'usage_limit_per_user' => 1,
                'is_active' => true,
                'is_first_order_only' => true,
                'terms_conditions' => 'Valid for first order only. Minimum order ₹500.',
            ],
            [
                'code' => 'VEGGIE20',
                'name' => 'Vegetables Special - 20% Off',
                'description' => 'Get 20% off on all vegetables. Stock up on fresh organic veggies!',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'minimum_order_amount' => 300.00,
                'maximum_discount_amount' => 500.00,
                'applicable_to' => 'category',
                'category_id' => $vegetablesCategory->id,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'usage_limit' => 500,
                'usage_limit_per_user' => 3,
                'is_active' => true,
                'is_first_order_only' => false,
                'terms_conditions' => 'Valid only on vegetables category. Minimum order ₹300.',
            ],
            [
                'code' => 'FRUIT15',
                'name' => 'Fruits Discount - 15% Off',
                'description' => 'Enjoy 15% off on all fresh organic fruits.',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'minimum_order_amount' => 400.00,
                'maximum_discount_amount' => 300.00,
                'applicable_to' => 'category',
                'category_id' => $fruitsCategory->id,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'usage_limit' => 300,
                'usage_limit_per_user' => 2,
                'is_active' => true,
                'is_first_order_only' => false,
                'terms_conditions' => 'Valid only on fruits category. Minimum order ₹400.',
            ],
            [
                'code' => 'FLAT100',
                'name' => 'Flat ₹100 Off',
                'description' => 'Get flat ₹100 discount on orders above ₹1000.',
                'discount_type' => 'fixed',
                'discount_value' => 100.00,
                'minimum_order_amount' => 1000.00,
                'maximum_discount_amount' => null,
                'applicable_to' => 'all',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(2),
                'usage_limit' => 200,
                'usage_limit_per_user' => 1,
                'is_active' => true,
                'is_first_order_only' => false,
                'terms_conditions' => 'Minimum order ₹1000. One time use per customer.',
            ],
            [
                'code' => 'ORGANIC25',
                'name' => 'Organic Valley Special - 25% Off',
                'description' => 'Get 25% off on all Organic Valley brand products.',
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'minimum_order_amount' => 500.00,
                'maximum_discount_amount' => 750.00,
                'applicable_to' => 'brand',
                'brand_id' => $organicValley->id,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'usage_limit' => 150,
                'usage_limit_per_user' => 2,
                'is_active' => true,
                'is_first_order_only' => false,
                'terms_conditions' => 'Valid only on Organic Valley products. Minimum order ₹500.',
            ],
            [
                'code' => 'WEEKEND50',
                'name' => 'Weekend Special - ₹50 Off',
                'description' => 'Weekend shopping special! Get ₹50 off on orders above ₹500.',
                'discount_type' => 'fixed',
                'discount_value' => 50.00,
                'minimum_order_amount' => 500.00,
                'maximum_discount_amount' => null,
                'applicable_to' => 'all',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'usage_limit' => 1000,
                'usage_limit_per_user' => 5,
                'is_active' => true,
                'is_first_order_only' => false,
                'terms_conditions' => 'Valid on weekends only. Minimum order ₹500.',
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
