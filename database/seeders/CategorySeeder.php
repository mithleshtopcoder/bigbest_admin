<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vegetables Category
        $vegetables = Category::create([
            'name' => 'Vegetables',
            'slug' => 'vegetables',
            'description' => 'Fresh organic vegetables sourced directly from certified farms. Farm to table in 24 hours.',
            'image' => 'categories/vegetables.jpg',
            'icon' => '🥬',
            'label' => 'Fresh & Organic',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Vegetables Sub-categories
        $vegetableSubCategories = [
            ['name' => 'Leafy Vegetables', 'slug' => 'leafy-vegetables', 'description' => 'Fresh organic spinach, lettuce, kale, and more', 'image' => 'subcategories/leafy-vegetables.jpg', 'icon' => '🥬', 'label' => 'Leafy Greens', 'sort_order' => 1],
            ['name' => 'Root Vegetables', 'slug' => 'root-vegetables', 'description' => 'Organic carrots, potatoes, onions, and root crops', 'image' => 'subcategories/root-vegetables.jpg', 'icon' => '🥕', 'label' => 'Root Crops', 'sort_order' => 2],
            ['name' => 'Tomatoes & Peppers', 'slug' => 'tomatoes-peppers', 'description' => 'Fresh organic tomatoes, bell peppers, and chilies', 'image' => 'subcategories/tomatoes-peppers.jpg', 'icon' => '🍅', 'label' => 'Fresh', 'sort_order' => 3],
            ['name' => 'Cucumbers & Gourds', 'slug' => 'cucumbers-gourds', 'description' => 'Organic cucumbers, bottle gourd, bitter gourd, and more', 'image' => 'subcategories/cucumbers-gourds.jpg', 'icon' => '🥒', 'label' => 'Hydrating', 'sort_order' => 4],
            ['name' => 'Beans & Peas', 'slug' => 'beans-peas', 'description' => 'Fresh organic green beans, peas, and legumes', 'image' => 'subcategories/beans-peas.jpg', 'icon' => '🫛', 'label' => 'Protein Rich', 'sort_order' => 5],
            ['name' => 'Cauliflower & Broccoli', 'slug' => 'cauliflower-broccoli', 'description' => 'Organic cauliflower, broccoli, and cabbage', 'image' => 'subcategories/cauliflower-broccoli.jpg', 'icon' => '🥦', 'label' => 'Nutritious', 'sort_order' => 6],
        ];

        foreach ($vegetableSubCategories as $subCat) {
            SubCategory::create([
                'category_id' => $vegetables->id,
                'name' => $subCat['name'],
                'slug' => $subCat['slug'],
                'description' => $subCat['description'],
                'image' => $subCat['image'],
                'icon' => $subCat['icon'],
                'label' => $subCat['label'],
                'sort_order' => $subCat['sort_order'],
                'is_active' => true,
            ]);
        }

        // Fruits Category
        $fruits = Category::create([
            'name' => 'Fruits',
            'slug' => 'fruits',
            'description' => 'Premium organic fruits, handpicked for freshness and quality. Seasonal and exotic varieties available.',
            'image' => 'categories/fruits.jpg',
            'icon' => '🍎',
            'label' => 'Premium Quality',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Fruits Sub-categories
        $fruitSubCategories = [
            ['name' => 'Citrus Fruits', 'slug' => 'citrus-fruits', 'description' => 'Fresh organic oranges, lemons, limes, and grapefruits', 'image' => 'subcategories/citrus-fruits.jpg', 'icon' => '🍊', 'label' => 'Vitamin C', 'sort_order' => 1],
            ['name' => 'Tropical Fruits', 'slug' => 'tropical-fruits', 'description' => 'Organic mangoes, pineapples, papayas, and bananas', 'image' => 'subcategories/tropical-fruits.jpg', 'icon' => '🥭', 'label' => 'Tropical', 'sort_order' => 2],
            ['name' => 'Berries', 'slug' => 'berries', 'description' => 'Fresh organic strawberries, blueberries, and raspberries', 'image' => 'subcategories/berries.jpg', 'icon' => '🫐', 'label' => 'Antioxidants', 'sort_order' => 3],
            ['name' => 'Stone Fruits', 'slug' => 'stone-fruits', 'description' => 'Organic peaches, plums, cherries, and apricots', 'image' => 'subcategories/stone-fruits.jpg', 'icon' => '🍑', 'label' => 'Sweet & Juicy', 'sort_order' => 4],
            ['name' => 'Melons', 'slug' => 'melons', 'description' => 'Fresh organic watermelons, muskmelons, and cantaloupes', 'image' => 'subcategories/melons.jpg', 'icon' => '🍉', 'label' => 'Hydrating', 'sort_order' => 5],
            ['name' => 'Apples & Pears', 'slug' => 'apples-pears', 'description' => 'Organic apples, pears, and seasonal varieties', 'image' => 'subcategories/apples-pears.jpg', 'icon' => '🍐', 'label' => 'Crisp & Fresh', 'sort_order' => 6],
        ];

        foreach ($fruitSubCategories as $subCat) {
            SubCategory::create([
                'category_id' => $fruits->id,
                'name' => $subCat['name'],
                'slug' => $subCat['slug'],
                'description' => $subCat['description'],
                'image' => $subCat['image'],
                'icon' => $subCat['icon'],
                'label' => $subCat['label'],
                'sort_order' => $subCat['sort_order'],
                'is_active' => true,
            ]);
        }

        // Organic Food Category
        $organicFood = Category::create([
            'name' => 'Organic Food',
            'slug' => 'organic-food',
            'description' => 'Certified organic food products including grains, pulses, spices, and packaged foods.',
            'image' => 'categories/organic-food.jpg',
            'icon' => '🌾',
            'label' => 'Certified Organic',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Organic Food Sub-categories
        $organicFoodSubCategories = [
            ['name' => 'Grains & Cereals', 'slug' => 'grains-cereals', 'description' => 'Organic rice, wheat, quinoa, and millets', 'image' => 'subcategories/grains-cereals.jpg', 'icon' => '🌾', 'label' => 'Whole Grains', 'sort_order' => 1],
            ['name' => 'Pulses & Legumes', 'slug' => 'pulses-legumes', 'description' => 'Organic lentils, beans, chickpeas, and dals', 'image' => 'subcategories/pulses-legumes.jpg', 'icon' => '🫘', 'label' => 'Protein Rich', 'sort_order' => 2],
            ['name' => 'Spices & Herbs', 'slug' => 'spices-herbs', 'description' => 'Organic spices, herbs, and seasonings', 'image' => 'subcategories/spices-herbs.jpg', 'icon' => '🌿', 'label' => 'Aromatic', 'sort_order' => 3],
            ['name' => 'Organic Oils', 'slug' => 'organic-oils', 'description' => 'Cold-pressed organic oils and ghee', 'image' => 'subcategories/organic-oils.jpg', 'icon' => '🫒', 'label' => 'Cold Pressed', 'sort_order' => 4],
            ['name' => 'Honey & Jams', 'slug' => 'honey-jams', 'description' => 'Pure organic honey, jams, and preserves', 'image' => 'subcategories/honey-jams.jpg', 'icon' => '🍯', 'label' => 'Natural Sweeteners', 'sort_order' => 5],
            ['name' => 'Snacks & Nuts', 'slug' => 'snacks-nuts', 'description' => 'Organic dry fruits, nuts, and healthy snacks', 'image' => 'subcategories/snacks-nuts.jpg', 'icon' => '🥜', 'label' => 'Healthy Snacks', 'sort_order' => 6],
        ];

        foreach ($organicFoodSubCategories as $subCat) {
            SubCategory::create([
                'category_id' => $organicFood->id,
                'name' => $subCat['name'],
                'slug' => $subCat['slug'],
                'description' => $subCat['description'],
                'image' => $subCat['image'],
                'icon' => $subCat['icon'],
                'label' => $subCat['label'],
                'sort_order' => $subCat['sort_order'],
                'is_active' => true,
            ]);
        }

        // Herbs & Greens Category
        $herbs = Category::create([
            'name' => 'Herbs & Greens',
            'slug' => 'herbs-greens',
            'description' => 'Fresh organic herbs, microgreens, and specialty greens for your kitchen.',
            'image' => 'categories/herbs-greens.jpg',
            'icon' => '🌿',
            'label' => 'Fresh Herbs',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Herbs & Greens Sub-categories
        $herbsSubCategories = [
            ['name' => 'Fresh Herbs', 'slug' => 'fresh-herbs', 'description' => 'Organic coriander, mint, basil, and curry leaves', 'image' => 'subcategories/fresh-herbs.jpg', 'icon' => '🌿', 'label' => 'Aromatic', 'sort_order' => 1],
            ['name' => 'Microgreens', 'slug' => 'microgreens', 'description' => 'Fresh organic microgreens and sprouts', 'image' => 'subcategories/microgreens.jpg', 'icon' => '🌱', 'label' => 'Superfood', 'sort_order' => 2],
            ['name' => 'Salad Greens', 'slug' => 'salad-greens', 'description' => 'Organic lettuce, arugula, and mixed salad greens', 'image' => 'subcategories/salad-greens.jpg', 'icon' => '🥗', 'label' => 'Fresh Salads', 'sort_order' => 3],
        ];

        foreach ($herbsSubCategories as $subCat) {
            SubCategory::create([
                'category_id' => $herbs->id,
                'name' => $subCat['name'],
                'slug' => $subCat['slug'],
                'description' => $subCat['description'],
                'image' => $subCat['image'],
                'icon' => $subCat['icon'],
                'label' => $subCat['label'],
                'sort_order' => $subCat['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
