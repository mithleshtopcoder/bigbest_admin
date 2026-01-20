@extends('layouts.app')

@section('title', 'Product Visibility')
@section('page-title', 'Product Visibility')
@section('page-description', 'Control product visibility in app/POS')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Product Visibility</h3>
            <p class="text-sm text-gray-500 mt-1">Visibility settings</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">POS</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">App</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Ring 22K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GR-22K-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">✓</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">✓</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Visible</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Chain 24K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GC-24K-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">✓</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">✓</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Visible</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Silver Bracelet</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SB-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">✓</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-red-600">✗</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">POS Only</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr></tbody></table></div></div>
</div>
@endsection
