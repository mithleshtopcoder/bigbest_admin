<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use Carbon\Carbon;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [

            // ===============================
            // Product ID 1 – Organic Wheat Flour
            // ===============================
            [
                'product_id' => 2,
                'name' => '1 Kg Pack',
                'code' => 'WF-1KG',
                'sku' => 'RG-WF-1KG',
                'barcode' => '8901111111111',
                'unit' => 'kg',
                'unit_value' => '1',
                'is_default' => 1,
                'is_active' => 1,
                'is_featured' => 1,
                'sort_order' => 1,
            ],
            [
                'product_id' => 5,
                'name' => '5 Kg Pack',
                'code' => 'WF-5KG',
                'sku' => 'RG-WF-5KG',
                'barcode' => '8901111111112',
                'unit' => 'kg',
                'unit_value' => '5',
                'is_default' => 0,
                'is_active' => 1,
                'is_featured' => 0,
                'sort_order' => 2,
            ],

            // ===============================
            // Product ID 2 – Organic Basmati Rice
            // ===============================
            [
                'product_id' => 2,
                'name' => '1 Kg Pack',
                'code' => 'RC-1KG',
                'sku' => 'RG-RC-1KG',
                'barcode' => '8902222222221',
                'unit' => 'kg',
                'unit_value' => '1',
                'is_default' => 1,
                'is_active' => 1,
                'is_featured' => 1,
                'sort_order' => 1,
            ],
            [
                'product_id' => 5,
                'name' => '10 Kg Pack',
                'code' => 'RC-10KG',
                'sku' => 'RG-RC-10KG',
                'barcode' => '8902222222222',
                'unit' => 'kg',
                'unit_value' => '10',
                'is_default' => 0,
                'is_active' => 1,
                'is_featured' => 0,
                'sort_order' => 2,
            ],

        ];

        foreach ($variants as $variant) {
            ProductVariant::create($variant);
        }
    }
}