@extends('layouts.app')

@section('title', 'Combo Offers')
@section('page-title', 'Combo Offers')
@section('page-description', 'Create combo product offers')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Combo Offers</h3>
            <p class="text-sm text-gray-500 mt-1">Bundle deals</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Combo Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Combo Price</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ring & Chain Combo</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Ring 22K + Gold Chain 24K</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹44,500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹38,000</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">15%</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jewelry Set</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ring + Earrings + Necklace</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹78,900</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹65,000</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">18%</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr></tbody></table></div></div>
</div>
@endsection
