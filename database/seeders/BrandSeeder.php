<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Organic Valley',
                'slug' => 'organic-valley',
                'description' => 'Premium organic food products including dairy, eggs, and produce. Certified organic and sustainably sourced.',
                'image' => 'brands/organic-valley.jpg',
                'logo' => 'brands/logos/organic-valley-logo.png',
                'website' => 'https://www.organicvalley.coop',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Nature\'s Path',
                'slug' => 'natures-path',
                'description' => 'Organic breakfast cereals, granola, and snacks. Family-owned and committed to organic farming.',
                'image' => 'brands/natures-path.jpg',
                'logo' => 'brands/logos/natures-path-logo.png',
                'website' => 'https://www.naturespath.com',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Earthbound Farm',
                'slug' => 'earthbound-farm',
                'description' => 'Fresh organic salads, fruits, and vegetables. Pioneers in organic farming since 1984.',
                'image' => 'brands/earthbound-farm.jpg',
                'logo' => 'brands/logos/earthbound-farm-logo.png',
                'website' => 'https://www.earthboundfarm.com',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Annie\'s Homegrown',
                'slug' => 'annies-homegrown',
                'description' => 'Organic snacks, pasta, and condiments. Made with real ingredients and no artificial flavors.',
                'image' => 'brands/annies-homegrown.jpg',
                'logo' => 'brands/logos/annies-homegrown-logo.png',
                'website' => 'https://www.annies.com',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => '365 by Whole Foods Market',
                'slug' => '365-whole-foods',
                'description' => 'Affordable organic products including produce, pantry staples, and snacks. Quality you can trust.',
                'image' => 'brands/365-whole-foods.jpg',
                'logo' => 'brands/logos/365-whole-foods-logo.png',
                'website' => 'https://www.wholefoodsmarket.com',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Amy\'s Kitchen',
                'slug' => 'amys-kitchen',
                'description' => 'Organic frozen meals, soups, and snacks. Vegetarian and vegan options available.',
                'image' => 'brands/amys-kitchen.jpg',
                'logo' => 'brands/logos/amys-kitchen-logo.png',
                'website' => 'https://www.amys.com',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Bob\'s Red Mill',
                'slug' => 'bobs-red-mill',
                'description' => 'Whole grain flours, cereals, and baking mixes. Stone-ground and organic options available.',
                'image' => 'brands/bobs-red-mill.jpg',
                'logo' => 'brands/logos/bobs-red-mill-logo.png',
                'website' => 'https://www.bobsredmill.com',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Lundberg Family Farms',
                'slug' => 'lundberg-family-farms',
                'description' => 'Organic rice, rice cakes, and rice-based products. Family farmed for over 80 years.',
                'image' => 'brands/lundberg-family-farms.jpg',
                'logo' => 'brands/logos/lundberg-family-farms-logo.png',
                'website' => 'https://www.lundberg.com',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Cascadian Farm',
                'slug' => 'cascadian-farm',
                'description' => 'Organic frozen fruits, vegetables, and snacks. Grown with care and respect for the environment.',
                'image' => 'brands/cascadian-farm.jpg',
                'logo' => 'brands/logos/cascadian-farm-logo.png',
                'website' => 'https://www.cascadianfarm.com',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Simply Organic',
                'slug' => 'simply-organic',
                'description' => 'Organic spices, seasonings, and extracts. Pure, potent, and certified organic.',
                'image' => 'brands/simply-organic.jpg',
                'logo' => 'brands/logos/simply-organic-logo.png',
                'website' => 'https://www.simplyorganic.com',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
