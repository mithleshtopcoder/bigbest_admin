<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductPrice;
use Carbon\Carbon;

class ProductPriceSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [

            [
                'product_variant_id' => 2,
                'price' => 260.00,
                'compare_at_price' => 300.00,
                'cost_price' => 215.00,
                'discount_percentage' => 13.33,
                'effective_from' => Carbon::now()->subDays(8),
                'effective_to' => null,
                'is_active' => true,
                'created_by' => 1,
            ],

            // =====================================
            // Variant ID 3 – Rice 1 Kg
            // =====================================
            [
                'product_variant_id' => 3,
                'price' => 95.00,
                'compare_at_price' => 110.00,
                'cost_price' => 78.00,
                'discount_percentage' => 13.64,
                'effective_from' => Carbon::now()->subDays(12),
                'effective_to' => null,
                'is_active' => true,
                'created_by' => 1,
            ],

            // =====================================
            // Variant ID 4 – Rice 10 Kg
            // =====================================
            [
                'product_variant_id' => 4,
                'price' => 880.00,
                'compare_at_price' => 1000.00,
                'cost_price' => 760.00,
                'discount_percentage' => 12.00,
                'effective_from' => Carbon::now()->subDays(6),
                'effective_to' => null,
                'is_active' => true,
                'created_by' => 1,
            ],

            // =====================================
            // Variant ID 5 – Oil 500 ml
            // =====================================
            [
                'product_variant_id' => 5,
                'price' => 145.00,
                'compare_at_price' => 165.00,
                'cost_price' => 120.00,
                'discount_percentage' => 12.12,
                'effective_from' => Carbon::now()->subDays(5),
                'effective_to' => null,
                'is_active' => true,
                'created_by' => 1,
            ],
        ];

        foreach ($prices as $price) {
            ProductPrice::create($price);
        }
    }
}