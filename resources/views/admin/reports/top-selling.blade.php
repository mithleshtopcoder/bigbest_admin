@extends('layouts.app')

@section('title', 'Top Selling')
@section('page-title', 'Top Selling Products')
@section('page-description', 'View top selling products')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Top Selling</h3>
            <p class="text-sm text-gray-500 mt-1">Best sellers</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Growth</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Ring 22K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GR-22K-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹24,33,600</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">+15%</span></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Chain 24K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">GC-24K-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">89</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹25,72,100</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">+12%</span></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Silver Bracelet</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SB-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">234</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹19,89,000</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">+8%</span></td></tr></tbody></table></div></div>
</div>
@endsection
