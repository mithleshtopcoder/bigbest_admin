<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductStock;
use Carbon\Carbon;

class ProductStockSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = [

            // =====================================
            // Variant ID 1 – Wheat Flour 1 Kg
            // =====================================
            [
                'product_variant_id' => 2,
                'store_id' => 1,
                'quantity' => 500,
                'reserved_quantity' => 20,
                'in_transit_quantity' => 50,
                'pending_quantity' => 10,
                'min_stock_level' => 50,
                'max_stock_level' => 1000,
                'stock_status' => 'in_stock',
                'last_restocked_at' => Carbon::now()->subDays(2),
                'last_restocked_by' => 1,
            ],

            [
                'product_variant_id' => 3,
                'store_id' => 1,
                'quantity' => 120,
                'reserved_quantity' => 10,
                'in_transit_quantity' => 0,
                'pending_quantity' => 5,
                'min_stock_level' => 50,
                'max_stock_level' => 500,
                'stock_status' => 'low_stock',
                'last_restocked_at' => Carbon::now()->subDays(5),
                'last_restocked_by' => 1,
            ],

            // =====================================
            // Variant ID 2 – Wheat Flour 5 Kg
            // =====================================
            [
                'product_variant_id' => 4,
                'store_id' => 1,
                'quantity' => 200,
                'reserved_quantity' => 15,
                'in_transit_quantity' => 30,
                'pending_quantity' => 8,
                'min_stock_level' => 30,
                'max_stock_level' => 400,
                'stock_status' => 'in_stock',
                'last_restocked_at' => Carbon::now()->subDays(1),
                'last_restocked_by' => 1,
            ],

          
        ];

        foreach ($stocks as $stock) {
            ProductStock::create($stock);
        }
    }
}