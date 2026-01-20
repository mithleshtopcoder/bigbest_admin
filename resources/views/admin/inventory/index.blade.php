@extends('layouts.app')

@section('title', 'Inventory Overview')
@section('page-title', 'Inventory Overview')
@section('page-description', 'Real-time stock levels across all stores')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Total Products</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">1,234</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Total Stock Value</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">₹45.67L</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">23</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Out of Stock</p>
            <p class="text-3xl font-bold text-red-600 mt-2">5</p>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Stock Summary</h3>
            <div class="flex items-center space-x-3">
                <input type="text" placeholder="Search products..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option>All Stores</option>
                    <option>Mumbai Central</option>
                    <option>Delhi NCR</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Gold Ring 22K</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">GR-22K-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">156 units</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹2,45,600</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                In Stock
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

