<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $products = [
            
            [
                'name' => 'Organic Basmati Rice',
                'slug' => Str::slug('Organic Basmati Rice'),
                'sku' => 'RG-RC-002',
                'description' => 'Long grain organic basmati rice.',
                'short_description' => 'Aromatic Organic Rice',
                'category_id' => 1,
                'sub_category_id' => 2,
                'brand_id' => 1,
                'tax_id' => null,
                'barcode' => '8901234567891',
                'item_type' => 1,
                'item_code' => 'ITEM002',
                'collection' => 1,
                'season' => 1,
                'length' => 25.00,
                'width' => 18.00,
                'height' => 6.00,
                'thumbnail_image' => 'basmati_rice.jpg',
                'min_order_quantity' => 1,
                'max_order_quantity' => 20,
                'status' => 1,           // weight
                'is_featured' => 0,
                'top_selling_product' => 1,
                'sort_order' => 2,
                'vendor_id' => 1,
                'approval_status' => 'approved',
                'approved_at' => $now,
                'approved_by' => 1,
            ],
            [
                'name' => 'Organic Cold Pressed Oil',
                'slug' => Str::slug('Organic Cold Pressed Oil'),
                'sku' => 'RG-OIL-003',
                'description' => 'Healthy cold pressed cooking oil.',
                'short_description' => 'Pure Cold Pressed Oil',
                'category_id' => 2,
                'sub_category_id' => 3,
                'brand_id' => 2,
                'tax_id' => null,
                'barcode' => '8901234567892',
                'item_type' => 1,
                'item_code' => 'ITEM003',
                'collection' => 2,
                'season' => 1,
                'length' => 10.00,
                'width' => 10.00,
                'height' => 30.00,
                'thumbnail_image' => 'cold_pressed_oil.jpg',
                'min_order_quantity' => 1,
                'max_order_quantity' => 5,
                'status' => 1,
                'variant_type' => 3,            // volume
                'is_featured' => 1,
                'top_selling_product' => 0,
                'sort_order' => 3,
                'vendor_id' => 2,
                'approval_status' => 'approved',
                'approved_at' => $now,
                'approved_by' => 1,
            ],
            [
                'name' => 'Organic Turmeric Powder',
                'slug' => Str::slug('Organic Turmeric Powder'),
                'sku' => 'RG-TUR-004',
                'description' => 'Pure organic turmeric powder.',
                'short_description' => 'Natural Turmeric',
                'category_id' => 3,
                'sub_category_id' => 4,
                'brand_id' => 3,
                'tax_id' => null,
                'barcode' => '8901234567893',
                'item_type' => 1,
                'item_code' => 'ITEM004',
                'collection' => 2,
                'season' => 1,
                'length' => 12.00,
                'width' => 8.00,
                'height' => 20.00,
                'thumbnail_image' => 'turmeric.jpg',
                'min_order_quantity' => 1,
                'max_order_quantity' => 10,
                'status' => 1,
                'variant_type' => 2,
                'is_featured' => 0,
                'top_selling_product' => 0,
                'sort_order' => 4,
                'vendor_id' => 2,
                'approval_status' => 'approved',
                'approved_at' => $now,
                'approved_by' => 1,
            ],
            [
                'name' => 'Organic Honey',
                'slug' => Str::slug('Organic Honey'),
                'sku' => 'RG-HNY-005',
                'description' => 'Raw and natural organic honey.',
                'short_description' => 'Pure Organic Honey',
                'category_id' => 1,
                'sub_category_id' => 1,
                'brand_id' => 1,
                'tax_id' => null,
                'barcode' => '8901234567894',
                'item_type' => 1,
                'item_code' => 'ITEM005',
                'collection' => 3,
                'season' => 1,
                'length' => 10.00,
                'width' => 10.00,
                'height' => 25.00,
                'packaging_type' => 4,         // 4 = Jar
                'thumbnail_image' => 'honey.jpg',
                'min_order_quantity' => 1,
                'max_order_quantity' => 5,
                'status' => 1,
                'is_featured' => 1,
                'top_selling_product' => 1,
                'sort_order' => 5,
                'vendor_id' => 3,
                'approval_status' => 'approved',
                'approved_at' => $now,
                'approved_by' => 1,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}