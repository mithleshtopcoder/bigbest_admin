@extends('layouts.app')

@section('title', 'Units & Attributes')
@section('page-title', 'Units & Attributes')
@section('page-description', 'Manage product units and attributes')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Units & Attributes</h3>
            <p class="text-sm text-gray-500 mt-1">Product attributes</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attribute</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Values</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Used In</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Size</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Numeric</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6, 7, 8, 9, 10</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rings</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Length</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Text</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">18 inch, 20 inch, 22 inch</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Chains</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Weight</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Numeric</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5g, 10g, 15g, 20g</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">All Products</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Purity</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Text</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">18K, 22K, 24K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Products</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr></tbody></table></div></div>
</div>
@endsection
