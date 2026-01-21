<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Policy;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = [
            [
                'type' => 'terms_conditions',
                'title' => 'Terms and Conditions',
                'content' => 'By using our services, you agree to our terms and conditions. All products are organic and certified. We ensure quality and freshness in every delivery. Big Best reserves the right to modify these terms at any time.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'type' => 'privacy_policy',
                'title' => 'Privacy Policy',
                'content' => 'We respect your privacy. Your personal information is securely stored and will never be shared with third parties without your consent. Big Best is committed to protecting your data and ensuring your privacy.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'type' => 'refund_policy',
                'title' => 'Refund Policy',
                'content' => 'We offer a 100% satisfaction guarantee. If you are not satisfied with your order, contact us within 24 hours for a full refund or replacement. Refunds will be processed within 5-7 business days.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'type' => 'shipping_policy',
                'title' => 'Shipping Policy',
                'content' => 'We deliver fresh products within 24-48 hours of order placement. Free delivery on orders above ₹500. Standard delivery charges apply for orders below ₹500. We ensure proper packaging to maintain product freshness.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'type' => 'about_us',
                'title' => 'About Us',
                'content' => 'Big Best is committed to providing the freshest organic produce to our customers. We work directly with local farmers to bring you the best quality vegetables, fruits, and food products. Our mission is to make organic food accessible to everyone.',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($policies as $policy) {
            Policy::updateOrCreate(
                ['type' => $policy['type']],
                $policy
            );
        }
    }
}
