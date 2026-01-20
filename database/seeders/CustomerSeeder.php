<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'first_name' => 'Info',
                'last_name' => 'RSTC',
                'email' => 'info@rstc.com',
                'phone' => '+91-9876543210',
                'password' => 'password123',
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'address' => '123 Main Street, Downtown',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'country' => 'India',
                'wallet_balance' => 500.00,
                'loyalty_points' => 250,
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Sharma',
                'email' => 'priya.sharma@example.com',
                'phone' => '+91-9876543211',
                'password' => 'password123',
                'date_of_birth' => '1992-08-20',
                'gender' => 'female',
                'address' => '456 Park Avenue, Suburban',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400070',
                'country' => 'India',
                'wallet_balance' => 750.50,
                'loyalty_points' => 180,
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ],
            [
                'first_name' => 'Amit',
                'last_name' => 'Patel',
                'email' => 'amit.patel@example.com',
                'phone' => '+91-9876543212',
                'password' => 'password123',
                'date_of_birth' => '1988-12-10',
                'gender' => 'male',
                'address' => '789 North Street, Andheri',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400053',
                'country' => 'India',
                'wallet_balance' => 300.00,
                'loyalty_points' => 100,
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ],
            [
                'first_name' => 'Sneha',
                'last_name' => 'Desai',
                'email' => 'sneha.desai@example.com',
                'phone' => '+91-9876543213',
                'password' => 'password123',
                'date_of_birth' => '1995-03-25',
                'gender' => 'female',
                'address' => '321 Garden Road, Bandra',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400050',
                'country' => 'India',
                'wallet_balance' => 1000.00,
                'loyalty_points' => 500,
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ],
            [
                'first_name' => 'Vikram',
                'last_name' => 'Singh',
                'email' => 'vikram.singh@example.com',
                'phone' => '+91-9876543214',
                'password' => 'password123',
                'date_of_birth' => '1991-07-18',
                'gender' => 'male',
                'address' => '654 Marine Drive, Colaba',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400005',
                'country' => 'India',
                'wallet_balance' => 200.00,
                'loyalty_points' => 75,
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
}

