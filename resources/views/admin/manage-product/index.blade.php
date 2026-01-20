@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Product Management')
@section('page-description', 'Manage product catalog and details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">All Products</h3>
            <p class="text-sm text-gray-500 mt-1">Manage your product catalog</p>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Product</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Products</h3>
            <input type="text" placeholder="Search products..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-64">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Gold Ring 22K</div>
                                    <div class="text-sm text-gray-500">Barcode: 1234567890123</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GR-22K-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rings</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹15,750</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156 units</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <button class="text-gray-600 hover:text-gray-900">View</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Gold Chain 24K</div>
                                    <div class="text-sm text-gray-500">Barcode: 1234567890124</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GC-24K-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Chains</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹19,300</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">98 units</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            <button class="text-gray-600 hover:text-gray-900">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

