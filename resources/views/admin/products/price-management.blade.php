@extends('layouts.app')

@section('title', 'Price Management')
@section('page-title', 'Price Management')
@section('page-description', 'Manage product pricing')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Price Management</h3>
            <p class="text-sm text-gray-500 mt-1">Product pricing</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Price</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store Price</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">App Price</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Ring 22K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GR-22K-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹15,600</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹15,600</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹14,800</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5%</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Chain 24K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GC-24K-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹28,900</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹28,900</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹27,500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5%</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Silver Bracelet</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SB-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,000</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6%</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr></tbody></table></div></div>
</div>
@endsection
