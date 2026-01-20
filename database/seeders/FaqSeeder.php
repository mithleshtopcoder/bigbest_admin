<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // Ordering & Delivery
            [
                'question' => 'How do I place an order?',
                'answer' => 'You can place an order through our mobile app. Simply browse our products, add items to your cart, and proceed to checkout. You can pay online or choose cash on delivery.',
                'category' => 'Ordering & Delivery',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What are your delivery timings?',
                'answer' => 'We deliver from 8 AM to 10 PM, 7 days a week. You can choose your preferred delivery slot during checkout. Same-day delivery is available for orders placed before 2 PM.',
                'category' => 'Ordering & Delivery',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Is there a minimum order value?',
                'answer' => 'Yes, the minimum order value is ₹200. However, we offer free delivery on orders above ₹500.',
                'category' => 'Ordering & Delivery',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What areas do you deliver to?',
                'answer' => 'We currently deliver within a 10 km radius from our store. You can check if your area is covered by entering your pincode on the delivery page.',
                'category' => 'Ordering & Delivery',
                'sort_order' => 4,
                'is_active' => true,
            ],
            // Products & Quality
            [
                'question' => 'Are your products certified organic?',
                'answer' => 'Yes, all our products are certified organic and sourced directly from certified organic farms. We ensure quality and freshness in every product.',
                'category' => 'Products & Quality',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How fresh are your vegetables and fruits?',
                'answer' => 'We source our products daily from local organic farms. Our vegetables and fruits are harvested fresh and delivered to you within 24 hours of harvest.',
                'category' => 'Products & Quality',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer seasonal products?',
                'answer' => 'Yes, we offer seasonal organic vegetables and fruits. Our inventory changes based on what\'s in season to ensure you get the freshest produce.',
                'category' => 'Products & Quality',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What if I receive damaged or spoiled products?',
                'answer' => 'We guarantee the quality of our products. If you receive any damaged or spoiled items, please contact us within 24 hours and we will replace them free of charge.',
                'category' => 'Products & Quality',
                'sort_order' => 4,
                'is_active' => true,
            ],
            // Payment & Refunds
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept all major payment methods including credit/debit cards, UPI, net banking, digital wallets, and cash on delivery.',
                'category' => 'Payment & Refunds',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How do I get a refund?',
                'answer' => 'Refunds are processed within 5-7 business days. For online payments, the amount will be credited back to your original payment method. For cash on delivery, refunds are processed via bank transfer.',
                'category' => 'Payment & Refunds',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Can I cancel my order?',
                'answer' => 'Yes, you can cancel your order before it\'s dispatched. Once dispatched, you can return the products and get a full refund. Please contact our customer support for assistance.',
                'category' => 'Payment & Refunds',
                'sort_order' => 3,
                'is_active' => true,
            ],
            // Account & Membership
            [
                'question' => 'Do you have a loyalty program?',
                'answer' => 'Yes! We have a loyalty points program. Earn points on every purchase and redeem them for discounts on future orders. You also get exclusive offers as a member.',
                'category' => 'Account & Membership',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How do I track my order?',
                'answer' => 'You can track your order in real-time through the app. You\'ll receive SMS and push notifications with order updates and delivery status.',
                'category' => 'Account & Membership',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Can I modify my order after placing it?',
                'answer' => 'You can modify your order within 30 minutes of placing it, provided it hasn\'t been dispatched. Please contact our customer support team for assistance.',
                'category' => 'Account & Membership',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
