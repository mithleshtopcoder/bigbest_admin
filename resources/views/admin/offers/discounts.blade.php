@extends('layouts.app')

@section('title', 'Discounts')
@section('page-title', 'Discount Management')
@section('page-description', 'Manage discount offers')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Discounts</h3>
            <p class="text-sm text-gray-500 mt-1">Discount offers</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid From</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid To</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Festival Sale</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Percentage</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">20%</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-15</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-02-15</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">234/500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">New Customer</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Fixed</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1000</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-01</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-12-31</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">89/200</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td></tr></tbody></table></div></div>
</div>
@endsection
