<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            // Home Banners
            [
                'title' => 'Fresh Organic Vegetables',
                'image' => 'image_1.jpg',
                'link' => '/categories/vegetables',
                'type' => 'home',
                'position' => 'top',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Premium Organic Fruits',
                'image' => 'image_2.jpg',
                'link' => '/categories/fruits',
                'type' => 'home',
                'position' => 'top',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Farm Fresh Daily Delivery',
                'image' => 'image_3.jpg',
                'link' => '/categories',
                'type' => 'home',
                'position' => 'middle',
                'sort_order' => 3,
                'is_active' => true,
            ],
            // Promotional Banners
            [
                'title' => 'Special Offer: 20% Off on Organic Vegetables',
                'image' => 'image_4.jpg',
                'link' => '/categories/vegetables',
                'type' => 'promotional',
                'position' => 'top',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Buy 2 Get 1 Free on Organic Fruits',
                'image' => 'image_5.jpg',
                'link' => '/categories/fruits',
                'type' => 'promotional',
                'position' => 'top',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'New Customer: Get 15% Off First Order',
                'image' => 'image_6.jpg',
                'link' => '/register',
                'type' => 'promotional',
                'position' => 'middle',
                'sort_order' => 3,
                'is_active' => true,
            ],
            // Category Banners
            [
                'title' => 'Organic Vegetables Collection',
                'image' => 'image_1.jpg',
                'link' => '/categories/vegetables',
                'type' => 'category',
                'position' => 'category',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Fresh Fruits Collection',
                'image' => 'image_2.jpg',
                'link' => '/categories/fruits',
                'type' => 'category',
                'position' => 'category',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
