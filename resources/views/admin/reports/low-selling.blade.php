@extends('layouts.app')

@section('title', 'Low Selling')
@section('page-title', 'Low Selling Products')
@section('page-description', 'View low selling products')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Low Selling</h3>
            <p class="text-sm text-gray-500 mt-1">Slow movers</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Since Sale</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Old Design Ring</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ODR-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹31,200</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">158 days</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Dead Stock</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Vintage Necklace</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">VN-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹40,600</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">122 days</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Dead Stock</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr></tbody></table></div></div>
</div>
@endsection
