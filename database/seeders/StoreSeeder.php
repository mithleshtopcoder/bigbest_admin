<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreServiceArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Organic Fresh - Downtown Store',
                'code' => 'STORE001',
                'email' => 'downtown@organicfresh.com',
                'phone' => '+91-9876543210',
                'address' => '123 Main Street, Downtown',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'country' => 'India',
                'latitude' => 19.0760,
                'longitude' => 72.8777,
                'manager_name' => 'Rajesh Kumar',
                'manager_phone' => '+91-9876543211',
                'status' => 'active',
                'is_online' => true,
                'opening_time' => '08:00:00',
                'closing_time' => '22:00:00',
                'service_areas' => [
                    ['area_name' => 'Downtown Zone', 'pincode' => '400001', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'radius_km' => 5, 'min_order_amount' => 200.00, 'delivery_charge' => 30.00],
                    ['area_name' => 'South Mumbai', 'pincode' => '400020', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'radius_km' => 8, 'min_order_amount' => 300.00, 'delivery_charge' => 50.00],
                ]
            ],
            [
                'name' => 'Organic Fresh - Suburban Store',
                'code' => 'STORE002',
                'email' => 'suburban@organicfresh.com',
                'phone' => '+91-9876543220',
                'address' => '456 Park Avenue, Suburban',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400070',
                'country' => 'India',
                'latitude' => 19.1074,
                'longitude' => 72.8302,
                'manager_name' => 'Priya Sharma',
                'manager_phone' => '+91-9876543221',
                'status' => 'active',
                'is_online' => true,
                'opening_time' => '08:00:00',
                'closing_time' => '22:00:00',
                'service_areas' => [
                    ['area_name' => 'Suburban Zone', 'pincode' => '400070', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'radius_km' => 6, 'min_order_amount' => 200.00, 'delivery_charge' => 25.00],
                    ['area_name' => 'Andheri', 'pincode' => '400053', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'radius_km' => 7, 'min_order_amount' => 250.00, 'delivery_charge' => 40.00],
                ]
            ],
            [
                'name' => 'Organic Fresh - North Store',
                'code' => 'STORE003',
                'email' => 'north@organicfresh.com',
                'phone' => '+91-9876543230',
                'address' => '789 North Street, North Mumbai',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400092',
                'country' => 'India',
                'latitude' => 19.2183,
                'longitude' => 72.9781,
                'manager_name' => 'Amit Patel',
                'manager_phone' => '+91-9876543231',
                'status' => 'active',
                'is_online' => true,
                'opening_time' => '08:00:00',
                'closing_time' => '22:00:00',
                'service_areas' => [
                    ['area_name' => 'North Zone', 'pincode' => '400092', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'radius_km' => 5, 'min_order_amount' => 200.00, 'delivery_charge' => 30.00],
                ]
            ],
        ];

        foreach ($stores as $storeData) {
            $serviceAreas = $storeData['service_areas'];
            unset($storeData['service_areas']);

            $store = Store::create($storeData);

            // Create service areas
            foreach ($serviceAreas as $area) {
                StoreServiceArea::create([
                    'store_id' => $store->id,
                    'area_name' => $area['area_name'],
                    'pincode' => $area['pincode'],
                    'city' => $area['city'],
                    'state' => $area['state'],
                    'radius_km' => $area['radius_km'],
                    'min_order_amount' => $area['min_order_amount'],
                    'delivery_charge' => $area['delivery_charge'],
                    'is_active' => true,
                ]);
            }
        }
    }
}