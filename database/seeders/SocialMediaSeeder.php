<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMedia = [
            [
                'platform' => 'facebook',
                'name' => 'Facebook',
                'url' => 'https://www.facebook.com/organicfoodstore',
                'icon' => 'fab fa-facebook',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'platform' => 'instagram',
                'name' => 'Instagram',
                'url' => 'https://www.instagram.com/organicfoodstore',
                'icon' => 'fab fa-instagram',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'platform' => 'twitter',
                'name' => 'Twitter',
                'url' => 'https://www.twitter.com/organicfoodstore',
                'icon' => 'fab fa-twitter',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'platform' => 'youtube',
                'name' => 'YouTube',
                'url' => 'https://www.youtube.com/organicfoodstore',
                'icon' => 'fab fa-youtube',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'platform' => 'linkedin',
                'name' => 'LinkedIn',
                'url' => 'https://www.linkedin.com/company/organicfoodstore',
                'icon' => 'fab fa-linkedin',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'platform' => 'whatsapp',
                'name' => 'WhatsApp',
                'url' => 'https://wa.me/1234567890',
                'icon' => 'fab fa-whatsapp',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($socialMedia as $media) {
            SocialMedia::create($media);
        }
    }
}
