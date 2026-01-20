<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories, sub-categories, brands, and stores
        $vegetablesCategory = Category::where('slug', 'vegetables')->first();
        $fruitsCategory = Category::where('slug', 'fruits')->first();
        $organicFoodCategory = Category::where('slug', 'organic-food')->first();
        $herbsCategory = Category::where('slug', 'herbs-greens')->first();

        $leafySubCat = SubCategory::where('slug', 'leafy-vegetables')->first();
        $rootSubCat = SubCategory::where('slug', 'root-vegetables')->first();
        $tomatoesSubCat = SubCategory::where('slug', 'tomatoes-peppers')->first();
        $citrusSubCat = SubCategory::where('slug', 'citrus-fruits')->first();
        $tropicalSubCat = SubCategory::where('slug', 'tropical-fruits')->first();
        $grainsSubCat = SubCategory::where('slug', 'grains-cereals')->first();
        $freshHerbsSubCat = SubCategory::where('slug', 'fresh-herbs')->first();

        $organicValley = Brand::where('slug', 'organic-valley')->first();
        $naturesPath = Brand::where('slug', 'natures-path')->first();
        $bobsRedMill = Brand::where('slug', 'bobs-red-mill')->first();

        $stores = Store::all();

        // Product 1: Organic Spinach
        $spinach = Product::create([
            'name' => 'Fresh Organic Spinach',
            'slug' => 'fresh-organic-spinach',
            'sku' => 'PROD001',
            'description' => 'Fresh organic spinach leaves, rich in iron and vitamins. Harvested daily from certified organic farms.',
            'short_description' => 'Fresh organic spinach, rich in iron',
            'category_id' => $vegetablesCategory->id,
            'sub_category_id' => $leafySubCat->id,
            'brand_id' => $organicValley->id,
            'tax_id' => null,
            'barcode' => '1234567890123',
            'packaging_type' => 'bunch',
            'shelf_life_days' => 3,
            'origin_country' => 'India',
            'ingredients' => 'Organic Spinach',
            'nutritional_info' => 'Rich in Iron, Vitamin A, Vitamin C, Folate',
            'storage_instructions' => 'Store in refrigerator. Keep in original packaging.',
            'thumbnail_image' => 'products/spinach.jpg',
            'min_order_quantity' => 1,
            'max_order_quantity' => 10,
            'status' => 'active',
            'variant_type' => 'multiple',
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        // Spinach Variants
        $spinachVariants = [
            ['name' => '250g Bunch', 'unit_value' => '250', 'unit' => 'gram', 'price' => 45.00, 'compare_price' => 50.00],
            ['name' => '500g Bunch', 'unit_value' => '500', 'unit' => 'gram', 'price' => 85.00, 'compare_price' => 95.00],
        ];

        foreach ($spinachVariants as $index => $variant) {
            $productVariant = ProductVariant::create([
                'product_id' => $spinach->id,
                'name' => $variant['name'],
                'code' => 'SPIN-' . ($index + 1),
                'sku' => 'SPIN-' . strtoupper(Str::random(6)),
                'unit' => $variant['unit'],
                'unit_value' => $variant['unit_value'],
                'sort_order' => $index + 1,
                'is_default' => $index === 0,
                'is_active' => true,
            ]);

            // Create price
            ProductPrice::create([
                'product_variant_id' => $productVariant->id,
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_price'],
                'cost_price' => $variant['price'] * 0.7,
                'discount_percentage' => (($variant['compare_price'] - $variant['price']) / $variant['compare_price']) * 100,
                'effective_from' => now(),
                'is_active' => true,
            ]);

            // Create stock for each store
            foreach ($stores as $store) {
                ProductStock::create([
                    'product_variant_id' => $productVariant->id,
                    'store_id' => $store->id,
                    'quantity' => rand(50, 200),
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => 20,
                    'max_stock_level' => 500,
                    'stock_status' => 'in_stock',
                ]);
            }
        }

        // Product Images
        ProductImage::create([
            'product_id' => $spinach->id,
            'image_path' => 'products/spinach-1.jpg',
            'alt_text' => 'Fresh Organic Spinach',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        // Product Attributes
        ProductAttribute::create(['product_id' => $spinach->id, 'attribute_name' => 'Organic Certified', 'attribute_value' => 'Yes', 'attribute_type' => 'boolean', 'sort_order' => 1]);
        ProductAttribute::create(['product_id' => $spinach->id, 'attribute_name' => 'Certification', 'attribute_value' => 'USDA Organic', 'attribute_type' => 'text', 'sort_order' => 2]);

        // Product 2: Organic Carrots
        $carrots = Product::create([
            'name' => 'Fresh Organic Carrots',
            'slug' => 'fresh-organic-carrots',
            'sku' => 'PROD002',
            'description' => 'Sweet and crunchy organic carrots, rich in beta-carotene. Farm fresh and pesticide-free.',
            'short_description' => 'Sweet organic carrots, rich in beta-carotene',
            'category_id' => $vegetablesCategory->id,
            'sub_category_id' => $rootSubCat->id,
            'brand_id' => $organicValley->id,
            'tax_id' => null,
            'barcode' => '1234567890124',
            'packaging_type' => 'loose',
            'shelf_life_days' => 7,
            'origin_country' => 'India',
            'ingredients' => 'Organic Carrots',
            'nutritional_info' => 'Rich in Beta-Carotene, Vitamin A, Fiber',
            'storage_instructions' => 'Store in cool, dry place. Refrigerate for longer shelf life.',
            'thumbnail_image' => 'products/carrots.jpg',
            'min_order_quantity' => 1,
            'max_order_quantity' => 20,
            'status' => 'active',
            'variant_type' => 'multiple',
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        $carrotVariants = [
            ['name' => '500g', 'unit_value' => '500', 'unit' => 'gram', 'price' => 60.00, 'compare_price' => 70.00],
            ['name' => '1kg', 'unit_value' => '1', 'unit' => 'kg', 'price' => 110.00, 'compare_price' => 130.00],
            ['name' => '2kg', 'unit_value' => '2', 'unit' => 'kg', 'price' => 200.00, 'compare_price' => 240.00],
        ];

        foreach ($carrotVariants as $index => $variant) {
            $productVariant = ProductVariant::create([
                'product_id' => $carrots->id,
                'name' => $variant['name'],
                'code' => 'CARR-' . ($index + 1),
                'sku' => 'CARR-' . strtoupper(Str::random(6)),
                'unit' => $variant['unit'],
                'unit_value' => $variant['unit_value'],
                'sort_order' => $index + 1,
                'is_default' => $index === 0,
                'is_active' => true,
            ]);

            ProductPrice::create([
                'product_variant_id' => $productVariant->id,
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_price'],
                'cost_price' => $variant['price'] * 0.7,
                'discount_percentage' => (($variant['compare_price'] - $variant['price']) / $variant['compare_price']) * 100,
                'effective_from' => now(),
                'is_active' => true,
            ]);

            foreach ($stores as $store) {
                ProductStock::create([
                    'product_variant_id' => $productVariant->id,
                    'store_id' => $store->id,
                    'quantity' => rand(30, 150),
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => 25,
                    'max_stock_level' => 300,
                    'stock_status' => 'in_stock',
                ]);
            }
        }

        ProductImage::create(['product_id' => $carrots->id, 'image_path' => 'products/carrots-1.jpg', 'alt_text' => 'Fresh Organic Carrots', 'sort_order' => 1, 'is_primary' => true]);
        ProductAttribute::create(['product_id' => $carrots->id, 'attribute_name' => 'Organic Certified', 'attribute_value' => 'Yes', 'attribute_type' => 'boolean', 'sort_order' => 1]);

        // Product 3: Organic Tomatoes
        $tomatoes = Product::create([
            'name' => 'Fresh Organic Tomatoes',
            'slug' => 'fresh-organic-tomatoes',
            'sku' => 'PROD003',
            'description' => 'Juicy and flavorful organic tomatoes, perfect for salads and cooking. Locally sourced from organic farms.',
            'short_description' => 'Juicy organic tomatoes, locally sourced',
            'category_id' => $vegetablesCategory->id,
            'sub_category_id' => $tomatoesSubCat->id,
            'brand_id' => null,
            'tax_id' => null,
            'barcode' => '1234567890125',
            'packaging_type' => 'loose',
            'shelf_life_days' => 5,
            'origin_country' => 'India',
            'ingredients' => 'Organic Tomatoes',
            'nutritional_info' => 'Rich in Lycopene, Vitamin C, Potassium',
            'storage_instructions' => 'Store at room temperature. Refrigerate only when fully ripe.',
            'thumbnail_image' => 'products/tomatoes.jpg',
            'min_order_quantity' => 1,
            'max_order_quantity' => 15,
            'status' => 'active',
            'variant_type' => 'multiple',
            'is_featured' => false,
            'sort_order' => 3,
        ]);

        $tomatoVariants = [
            ['name' => '500g', 'unit_value' => '500', 'unit' => 'gram', 'price' => 55.00, 'compare_price' => 65.00],
            ['name' => '1kg', 'unit_value' => '1', 'unit' => 'kg', 'price' => 100.00, 'compare_price' => 120.00],
        ];

        foreach ($tomatoVariants as $index => $variant) {
            $productVariant = ProductVariant::create([
                'product_id' => $tomatoes->id,
                'name' => $variant['name'],
                'code' => 'TOMA-' . ($index + 1),
                'sku' => 'TOMA-' . strtoupper(Str::random(6)),
                'unit' => $variant['unit'],
                'unit_value' => $variant['unit_value'],
                'sort_order' => $index + 1,
                'is_default' => $index === 0,
                'is_active' => true,
            ]);

            ProductPrice::create([
                'product_variant_id' => $productVariant->id,
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_price'],
                'cost_price' => $variant['price'] * 0.7,
                'discount_percentage' => (($variant['compare_price'] - $variant['price']) / $variant['compare_price']) * 100,
                'effective_from' => now(),
                'is_active' => true,
            ]);

            foreach ($stores as $store) {
                ProductStock::create([
                    'product_variant_id' => $productVariant->id,
                    'store_id' => $store->id,
                    'quantity' => rand(40, 180),
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => 30,
                    'max_stock_level' => 400,
                    'stock_status' => 'in_stock',
                ]);
            }
        }

        ProductImage::create(['product_id' => $tomatoes->id, 'image_path' => 'products/tomatoes-1.jpg', 'alt_text' => 'Fresh Organic Tomatoes', 'sort_order' => 1, 'is_primary' => true]);

        // Product 4: Organic Oranges
        $oranges = Product::create([
            'name' => 'Fresh Organic Oranges',
            'slug' => 'fresh-organic-oranges',
            'sku' => 'PROD004',
            'description' => 'Sweet and juicy organic oranges, packed with Vitamin C. Perfect for daily nutrition.',
            'short_description' => 'Sweet organic oranges, rich in Vitamin C',
            'category_id' => $fruitsCategory->id,
            'sub_category_id' => $citrusSubCat->id,
            'brand_id' => null,
            'tax_id' => null,
            'barcode' => '1234567890126',
            'packaging_type' => 'loose',
            'shelf_life_days' => 10,
            'origin_country' => 'India',
            'ingredients' => 'Organic Oranges',
            'nutritional_info' => 'Rich in Vitamin C, Fiber, Folate',
            'storage_instructions' => 'Store at room temperature or refrigerate.',
            'thumbnail_image' => 'products/oranges.jpg',
            'min_order_quantity' => 1,
            'max_order_quantity' => 10,
            'status' => 'active',
            'variant_type' => 'multiple',
            'is_featured' => true,
            'sort_order' => 4,
        ]);

        $orangeVariants = [
            ['name' => '1kg (6-8 pieces)', 'unit_value' => '1', 'unit' => 'kg', 'price' => 120.00, 'compare_price' => 140.00],
            ['name' => '2kg (12-15 pieces)', 'unit_value' => '2', 'unit' => 'kg', 'price' => 220.00, 'compare_price' => 260.00],
        ];

        foreach ($orangeVariants as $index => $variant) {
            $productVariant = ProductVariant::create([
                'product_id' => $oranges->id,
                'name' => $variant['name'],
                'code' => 'ORAN-' . ($index + 1),
                'sku' => 'ORAN-' . strtoupper(Str::random(6)),
                'unit' => $variant['unit'],
                'unit_value' => $variant['unit_value'],
                'sort_order' => $index + 1,
                'is_default' => $index === 0,
                'is_active' => true,
            ]);

            ProductPrice::create([
                'product_variant_id' => $productVariant->id,
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_price'],
                'cost_price' => $variant['price'] * 0.7,
                'discount_percentage' => (($variant['compare_price'] - $variant['price']) / $variant['compare_price']) * 100,
                'effective_from' => now(),
                'is_active' => true,
            ]);

            foreach ($stores as $store) {
                ProductStock::create([
                    'product_variant_id' => $productVariant->id,
                    'store_id' => $store->id,
                    'quantity' => rand(25, 120),
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => 20,
                    'max_stock_level' => 250,
                    'stock_status' => 'in_stock',
                ]);
            }
        }

        ProductImage::create(['product_id' => $oranges->id, 'image_path' => 'products/oranges-1.jpg', 'alt_text' => 'Fresh Organic Oranges', 'sort_order' => 1, 'is_primary' => true]);
        ProductAttribute::create(['product_id' => $oranges->id, 'attribute_name' => 'Organic Certified', 'attribute_value' => 'Yes', 'attribute_type' => 'boolean', 'sort_order' => 1]);

        // Product 5: Organic Mangoes
        $mangoes = Product::create([
            'name' => 'Premium Organic Mangoes',
            'slug' => 'premium-organic-mangoes',
            'sku' => 'PROD005',
            'description' => 'Sweet and aromatic organic mangoes, the king of fruits. Seasonal and handpicked for quality.',
            'short_description' => 'Sweet organic mangoes, king of fruits',
            'category_id' => $fruitsCategory->id,
            'sub_category_id' => $tropicalSubCat->id,
            'brand_id' => null,
            'tax_id' => null,
            'barcode' => '1234567890127',
            'packaging_type' => 'loose',
            'shelf_life_days' => 7,
            'origin_country' => 'India',
            'ingredients' => 'Organic Mangoes',
            'nutritional_info' => 'Rich in Vitamin A, Vitamin C, Fiber',
            'storage_instructions' => 'Store at room temperature until ripe, then refrigerate.',
            'thumbnail_image' => 'products/mangoes.jpg',
            'min_order_quantity' => 1,
            'max_order_quantity' => 10,
            'status' => 'active',
            'variant_type' => 'multiple',
            'is_featured' => true,
            'sort_order' => 5,
        ]);

        $mangoVariants = [
            ['name' => '1kg (2-3 pieces)', 'unit_value' => '1', 'unit' => 'kg', 'price' => 180.00, 'compare_price' => 220.00],
            ['name' => '2kg (4-6 pieces)', 'unit_value' => '2', 'unit' => 'kg', 'price' => 340.00, 'compare_price' => 420.00],
        ];

        foreach ($mangoVariants as $index => $variant) {
            $productVariant = ProductVariant::create([
                'product_id' => $mangoes->id,
                'name' => $variant['name'],
                'code' => 'MANG-' . ($index + 1),
                'sku' => 'MANG-' . strtoupper(Str::random(6)),
                'unit' => $variant['unit'],
                'unit_value' => $variant['unit_value'],
                'sort_order' => $index + 1,
                'is_default' => $index === 0,
                'is_active' => true,
            ]);

            ProductPrice::create([
                'product_variant_id' => $productVariant->id,
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_price'],
                'cost_price' => $variant['price'] * 0.7,
                'discount_percentage' => (($variant['compare_price'] - $variant['price']) / $variant['compare_price']) * 100,
                'effective_from' => now(),
                'is_active' => true,
            ]);

            foreach ($stores as $store) {
                ProductStock::create([
                    'product_variant_id' => $productVariant->id,
                    'store_id' => $store->id,
                    'quantity' => rand(20, 100),
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => 15,
                    'max_stock_level' => 200,
                    'stock_status' => 'in_stock',
                ]);
            }
        }

        ProductImage::create(['product_id' => $mangoes->id, 'image_path' => 'products/mangoes-1.jpg', 'alt_text' => 'Premium Organic Mangoes', 'sort_order' => 1, 'is_primary' => true]);

        // Product 6: Organic Brown Rice
        $brownRice = Product::create([
            'name' => 'Organic Brown Rice',
            'slug' => 'organic-brown-rice',
            'sku' => 'PROD006',
            'description' => 'Premium organic brown rice, unpolished and nutrient-rich. Perfect for healthy meals.',
            'short_description' => 'Premium organic brown rice, unpolished',
            'category_id' => $organicFoodCategory->id,
            'sub_category_id' => $grainsSubCat->id,
            'brand_id' => $bobsRedMill->id,
            'tax_id' => null,
            'barcode' => '1234567890128',
            'packaging_type' => 'packet',
            'shelf_life_days' => 365,
            'origin_country' => 'India',
            'ingredients' => 'Organic Brown Rice',
            'nutritional_info' => 'Rich in Fiber, B Vitamins, Magnesium',
            'storage_instructions' => 'Store in cool, dry place in airtight container.',
            'thumbnail_image' => 'products/brown-rice.jpg',
            'min_order_quantity' => 1,
            'max_order_quantity' => 10,
            'status' => 'active',
            'variant_type' => 'multiple',
            'is_featured' => false,
            'sort_order' => 6,
        ]);

        $riceVariants = [
            ['name' => '1kg', 'unit_value' => '1', 'unit' => 'kg', 'price' => 150.00, 'compare_price' => 180.00],
            ['name' => '2kg', 'unit_value' => '2', 'unit' => 'kg', 'price' => 280.00, 'compare_price' => 340.00],
            ['name' => '5kg', 'unit_value' => '5', 'unit' => 'kg', 'price' => 650.00, 'compare_price' => 800.00],
        ];

        foreach ($riceVariants as $index => $variant) {
            $productVariant = ProductVariant::create([
                'product_id' => $brownRice->id,
                'name' => $variant['name'],
                'code' => 'RICE-' . ($index + 1),
                'sku' => 'RICE-' . strtoupper(Str::random(6)),
                'unit' => $variant['unit'],
                'unit_value' => $variant['unit_value'],
                'sort_order' => $index + 1,
                'is_default' => $index === 0,
                'is_active' => true,
            ]);

            ProductPrice::create([
                'product_variant_id' => $productVariant->id,
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_price'],
                'cost_price' => $variant['price'] * 0.7,
                'discount_percentage' => (($variant['compare_price'] - $variant['price']) / $variant['compare_price']) * 100,
                'effective_from' => now(),
                'is_active' => true,
            ]);

            foreach ($stores as $store) {
                ProductStock::create([
                    'product_variant_id' => $productVariant->id,
                    'store_id' => $store->id,
                    'quantity' => rand(50, 200),
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => 30,
                    'max_stock_level' => 500,
                    'stock_status' => 'in_stock',
                ]);
            }
        }

        ProductImage::create(['product_id' => $brownRice->id, 'image_path' => 'products/brown-rice-1.jpg', 'alt_text' => 'Organic Brown Rice', 'sort_order' => 1, 'is_primary' => true]);
        ProductAttribute::create(['product_id' => $brownRice->id, 'attribute_name' => 'Organic Certified', 'attribute_value' => 'Yes', 'attribute_type' => 'boolean', 'sort_order' => 1]);
        ProductAttribute::create(['product_id' => $brownRice->id, 'attribute_name' => 'Certification', 'attribute_value' => 'USDA Organic', 'attribute_type' => 'text', 'sort_order' => 2]);

        // Product 7: Fresh Coriander
        $coriander = Product::create([
            'name' => 'Fresh Organic Coriander',
            'slug' => 'fresh-organic-coriander',
            'sku' => 'PROD007',
            'description' => 'Fresh organic coriander leaves, aromatic and flavorful. Perfect for garnishing and cooking.',
            'short_description' => 'Fresh organic coriander leaves',
            'category_id' => $herbsCategory->id,
            'sub_category_id' => $freshHerbsSubCat->id,
            'brand_id' => null,
            'tax_id' => null,
            'barcode' => '1234567890129',
            'packaging_type' => 'bunch',
            'shelf_life_days' => 2,
            'origin_country' => 'India',
            'ingredients' => 'Organic Coriander',
            'nutritional_info' => 'Rich in Vitamin K, Vitamin C, Antioxidants',
            'storage_instructions' => 'Store in refrigerator. Keep stems in water for freshness.',
            'thumbnail_image' => 'products/coriander.jpg',
            'min_order_quantity' => 1,
            'max_order_quantity' => 5,
            'status' => 'active',
            'variant_type' => 'multiple',
            'is_featured' => false,
            'sort_order' => 7,
        ]);

        $corianderVariants = [
            ['name' => '100g Bunch', 'unit_value' => '100', 'unit' => 'gram', 'price' => 25.00, 'compare_price' => 30.00],
            ['name' => '250g Bunch', 'unit_value' => '250', 'unit' => 'gram', 'price' => 55.00, 'compare_price' => 65.00],
        ];

        foreach ($corianderVariants as $index => $variant) {
            $productVariant = ProductVariant::create([
                'product_id' => $coriander->id,
                'name' => $variant['name'],
                'code' => 'CORI-' . ($index + 1),
                'sku' => 'CORI-' . strtoupper(Str::random(6)),
                'unit' => $variant['unit'],
                'unit_value' => $variant['unit_value'],
                'sort_order' => $index + 1,
                'is_default' => $index === 0,
                'is_active' => true,
            ]);

            ProductPrice::create([
                'product_variant_id' => $productVariant->id,
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_price'],
                'cost_price' => $variant['price'] * 0.7,
                'discount_percentage' => (($variant['compare_price'] - $variant['price']) / $variant['compare_price']) * 100,
                'effective_from' => now(),
                'is_active' => true,
            ]);

            foreach ($stores as $store) {
                ProductStock::create([
                    'product_variant_id' => $productVariant->id,
                    'store_id' => $store->id,
                    'quantity' => rand(60, 250),
                    'reserved_quantity' => 0,
                    'in_transit_quantity' => 0,
                    'pending_quantity' => 0,
                    'min_stock_level' => 30,
                    'max_stock_level' => 600,
                    'stock_status' => 'in_stock',
                ]);
            }
        }

        ProductImage::create(['product_id' => $coriander->id, 'image_path' => 'products/coriander-1.jpg', 'alt_text' => 'Fresh Organic Coriander', 'sort_order' => 1, 'is_primary' => true]);
    }
}
