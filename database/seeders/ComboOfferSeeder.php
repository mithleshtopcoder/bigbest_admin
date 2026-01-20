<?php

namespace Database\Seeders;

use App\Models\ComboOffer;
use App\Models\ComboOfferItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComboOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spinach = Product::where('slug', 'fresh-organic-spinach')->first();
        $carrots = Product::where('slug', 'fresh-organic-carrots')->first();
        $tomatoes = Product::where('slug', 'fresh-organic-tomatoes')->first();
        $oranges = Product::where('slug', 'fresh-organic-oranges')->first();
        $coriander = Product::where('slug', 'fresh-organic-coriander')->first();

        if (!$spinach || !$carrots || !$tomatoes || !$oranges || !$coriander) {
            return; // Products not seeded yet
        }

        // Get default variants
        $spinachVariant = $spinach->variants()->where('is_default', true)->first();
        $carrotsVariant = $carrots->variants()->where('is_default', true)->first();
        $tomatoesVariant = $tomatoes->variants()->where('is_default', true)->first();
        $orangesVariant = $oranges->variants()->where('is_default', true)->first();
        $corianderVariant = $coriander->variants()->where('is_default', true)->first();

        // Combo 1: Fresh Vegetable Pack
        if ($spinachVariant && $carrotsVariant && $tomatoesVariant) {
            $combo1 = ComboOffer::create([
                'name' => 'Fresh Vegetable Combo Pack',
                'slug' => 'fresh-vegetable-combo-pack',
                'description' => 'Get fresh organic spinach, carrots, and tomatoes in one combo pack. Perfect for daily cooking!',
                'image' => 'combos/vegetable-combo.jpg',
                'original_price' => 200.00,
                'discounted_price' => 150.00,
                'discount_amount' => 50.00,
                'discount_percentage' => 25.00,
                'min_quantity' => 1,
                'max_quantity' => 5,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(2),
                'stock_quantity' => 100,
                'priority' => 10,
                'is_active' => true,
                'is_featured' => true,
                'terms_conditions' => 'Limited stock. Valid while supplies last.',
            ]);

            // Add items to combo
            $items1 = [
                ['product_id' => $spinach->id, 'product_variant_id' => $spinachVariant->id, 'quantity' => 1, 'unit_price' => 45.00, 'total_price' => 45.00],
                ['product_id' => $carrots->id, 'product_variant_id' => $carrotsVariant->id, 'quantity' => 1, 'unit_price' => 60.00, 'total_price' => 60.00],
                ['product_id' => $tomatoes->id, 'product_variant_id' => $tomatoesVariant->id, 'quantity' => 1, 'unit_price' => 55.00, 'total_price' => 55.00],
            ];

            foreach ($items1 as $index => $item) {
                ComboOfferItem::create([
                    'combo_offer_id' => $combo1->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        // Combo 2: Healthy Breakfast Pack
        if ($orangesVariant && $spinachVariant) {
            $combo2 = ComboOffer::create([
                'name' => 'Healthy Breakfast Combo',
                'slug' => 'healthy-breakfast-combo',
                'description' => 'Start your day right with fresh oranges and organic spinach. Perfect for a healthy breakfast!',
                'image' => 'combos/breakfast-combo.jpg',
                'original_price' => 165.00,
                'discounted_price' => 130.00,
                'discount_amount' => 35.00,
                'discount_percentage' => 21.21,
                'min_quantity' => 1,
                'max_quantity' => 3,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'stock_quantity' => 50,
                'priority' => 8,
                'is_active' => true,
                'is_featured' => true,
                'terms_conditions' => 'Limited stock available.',
            ]);

            $items2 = [
                ['product_id' => $oranges->id, 'product_variant_id' => $orangesVariant->id, 'quantity' => 1, 'unit_price' => 120.00, 'total_price' => 120.00],
                ['product_id' => $spinach->id, 'product_variant_id' => $spinachVariant->id, 'quantity' => 1, 'unit_price' => 45.00, 'total_price' => 45.00],
            ];

            foreach ($items2 as $index => $item) {
                ComboOfferItem::create([
                    'combo_offer_id' => $combo2->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'sort_order' => $index + 1,
                ]);
            }
        }

        // Combo 3: Complete Cooking Essentials
        if ($spinachVariant && $carrotsVariant && $tomatoesVariant && $corianderVariant) {
            $combo3 = ComboOffer::create([
                'name' => 'Complete Cooking Essentials',
                'slug' => 'complete-cooking-essentials',
                'description' => 'Everything you need for cooking - spinach, carrots, tomatoes, and fresh coriander. Complete meal prep combo!',
                'image' => 'combos/cooking-essentials.jpg',
                'original_price' => 230.00,
                'discounted_price' => 180.00,
                'discount_amount' => 50.00,
                'discount_percentage' => 21.74,
                'min_quantity' => 1,
                'max_quantity' => 5,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(2),
                'stock_quantity' => 75,
                'priority' => 9,
                'is_active' => true,
                'is_featured' => true,
                'terms_conditions' => 'Perfect for meal prep. Limited stock.',
            ]);

            $items3 = [
                ['product_id' => $spinach->id, 'product_variant_id' => $spinachVariant->id, 'quantity' => 1, 'unit_price' => 45.00, 'total_price' => 45.00],
                ['product_id' => $carrots->id, 'product_variant_id' => $carrotsVariant->id, 'quantity' => 1, 'unit_price' => 60.00, 'total_price' => 60.00],
                ['product_id' => $tomatoes->id, 'product_variant_id' => $tomatoesVariant->id, 'quantity' => 1, 'unit_price' => 55.00, 'total_price' => 55.00],
                ['product_id' => $coriander->id, 'product_variant_id' => $corianderVariant->id, 'quantity' => 1, 'unit_price' => 25.00, 'total_price' => 25.00],
            ];

            foreach ($items3 as $index => $item) {
                ComboOfferItem::create([
                    'combo_offer_id' => $combo3->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
