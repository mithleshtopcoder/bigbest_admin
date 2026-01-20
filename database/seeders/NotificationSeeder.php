<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        foreach ($customers as $customer) {
            // Create a mix of different notification types for each customer
            $notifications = [
                // Order notifications
                [
                    'customer_id' => $customer->id,
                    'type' => 'order',
                    'title' => 'Order Confirmed',
                    'message' => "Your order #ORD{$customer->id}001 has been confirmed and is being processed.",
                    'data' => [
                        'order_id' => "ORD{$customer->id}001",
                        'status' => 'confirmed',
                    ],
                    'action_url' => "/orders/ORD{$customer->id}001",
                    'is_read' => false,
                    'sent_at' => now()->subHours(2),
                ],
                [
                    'customer_id' => $customer->id,
                    'type' => 'order',
                    'title' => 'Order Shipped',
                    'message' => "Your order #ORD{$customer->id}002 has been shipped and is on its way.",
                    'data' => [
                        'order_id' => "ORD{$customer->id}002",
                        'status' => 'shipped',
                        'tracking_number' => "TRK{$customer->id}12345",
                    ],
                    'action_url' => "/orders/ORD{$customer->id}002",
                    'is_read' => true,
                    'read_at' => now()->subHours(1),
                    'sent_at' => now()->subDays(1),
                ],
                [
                    'customer_id' => $customer->id,
                    'type' => 'order',
                    'title' => 'Order Delivered',
                    'message' => "Your order #ORD{$customer->id}003 has been delivered successfully. Thank you for shopping with us!",
                    'data' => [
                        'order_id' => "ORD{$customer->id}003",
                        'status' => 'delivered',
                    ],
                    'action_url' => "/orders/ORD{$customer->id}003",
                    'is_read' => true,
                    'read_at' => now()->subDays(2),
                    'sent_at' => now()->subDays(3),
                ],

                // Promotion notifications
                [
                    'customer_id' => $customer->id,
                    'type' => 'promotion',
                    'title' => 'Special Offer - 20% Off',
                    'message' => 'Get 20% off on all organic vegetables. Use code VEG20 at checkout. Valid until end of month.',
                    'data' => [
                        'offer_id' => 'OFFER001',
                        'discount' => 20,
                        'code' => 'VEG20',
                    ],
                    'action_url' => '/offers/vegetables-special',
                    'is_read' => false,
                    'sent_at' => now()->subHours(5),
                ],
                [
                    'customer_id' => $customer->id,
                    'type' => 'promotion',
                    'title' => 'New Arrivals - Fresh Fruits',
                    'message' => 'Check out our new collection of fresh seasonal fruits. Limited stock available!',
                    'data' => [
                        'offer_id' => 'OFFER002',
                        'category' => 'fruits',
                    ],
                    'action_url' => '/products?category=fruits',
                    'is_read' => true,
                    'read_at' => now()->subDays(1),
                    'sent_at' => now()->subDays(2),
                ],

                // Payment notifications
                [
                    'customer_id' => $customer->id,
                    'type' => 'payment',
                    'title' => 'Payment Successful',
                    'message' => "Your payment of ₹1,250.00 for order #ORD{$customer->id}001 has been processed successfully.",
                    'data' => [
                        'payment_id' => "PAY{$customer->id}001",
                        'status' => 'success',
                        'amount' => 1250.00,
                    ],
                    'action_url' => "/payments/PAY{$customer->id}001",
                    'is_read' => false,
                    'sent_at' => now()->subHours(3),
                ],
                [
                    'customer_id' => $customer->id,
                    'type' => 'payment',
                    'title' => 'Payment Failed',
                    'message' => "Your payment for order #ORD{$customer->id}004 could not be processed. Please try again or use a different payment method.",
                    'data' => [
                        'payment_id' => "PAY{$customer->id}002",
                        'status' => 'failed',
                        'order_id' => "ORD{$customer->id}004",
                    ],
                    'action_url' => "/payments/PAY{$customer->id}002",
                    'is_read' => true,
                    'read_at' => now()->subDays(1),
                    'sent_at' => now()->subDays(1),
                ],

                // Delivery notifications
                [
                    'customer_id' => $customer->id,
                    'type' => 'delivery',
                    'title' => 'Out for Delivery',
                    'message' => "Your order #ORD{$customer->id}002 is out for delivery. Expected delivery time: 2-3 hours.",
                    'data' => [
                        'order_id' => "ORD{$customer->id}002",
                        'estimated_time' => '2-3 hours',
                    ],
                    'action_url' => "/orders/ORD{$customer->id}002",
                    'is_read' => false,
                    'sent_at' => now()->subHours(1),
                ],
                [
                    'customer_id' => $customer->id,
                    'type' => 'delivery',
                    'title' => 'Delivery Rescheduled',
                    'message' => "Your delivery for order #ORD{$customer->id}005 has been rescheduled to tomorrow due to weather conditions.",
                    'data' => [
                        'order_id' => "ORD{$customer->id}005",
                        'reason' => 'weather',
                    ],
                    'action_url' => "/orders/ORD{$customer->id}005",
                    'is_read' => true,
                    'read_at' => now()->subDays(1),
                    'sent_at' => now()->subDays(2),
                ],

                // System notifications
                [
                    'customer_id' => $customer->id,
                    'type' => 'system',
                    'title' => 'Welcome to RG Organic Mart!',
                    'message' => "Thank you for joining us! Get ₹100 welcome bonus in your wallet. Start shopping now!",
                    'data' => [
                        'welcome_bonus' => 100,
                    ],
                    'action_url' => '/products',
                    'is_read' => true,
                    'read_at' => now()->subDays(7),
                    'sent_at' => now()->subDays(7),
                ],
                [
                    'customer_id' => $customer->id,
                    'type' => 'system',
                    'title' => 'Loyalty Points Added',
                    'message' => "You've earned 50 loyalty points from your recent purchase. Keep shopping to earn more rewards!",
                    'data' => [
                        'points' => 50,
                        'total_points' => $customer->loyalty_points,
                    ],
                    'action_url' => '/customer/loyalty-points',
                    'is_read' => false,
                    'sent_at' => now()->subDays(1),
                ],
            ];

            foreach ($notifications as $notificationData) {
                Notification::create($notificationData);
            }
        }

        $this->command->info('Notifications seeded successfully for ' . $customers->count() . ' customers.');
    }
}

