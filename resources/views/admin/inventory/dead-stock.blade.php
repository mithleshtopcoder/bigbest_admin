@extends('layouts.app')

@section('title', 'Dead Stock')
@section('page-title', 'Dead Stock Report')
@section('page-description', 'View non-moving inventory')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Dead Stock</h3>
            <p class="text-sm text-gray-500 mt-1">Non-moving items</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Sold</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Since Sale</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Old Design Ring</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ODR-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2023-08-15</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">158 days</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,87,200</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Discount</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Vintage Necklace</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">VN-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2023-09-20</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">122 days</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹3,24,800</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Discount</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Classic Bracelet</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CB-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">15</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2023-10-10</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">102 days</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,27,500</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Discount</a></td></tr></tbody></table></div></div>
</div>
@endsection
