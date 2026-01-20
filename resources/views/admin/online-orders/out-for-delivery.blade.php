@extends('layouts.app')

@section('title', 'Out for Delivery')
@section('page-title', 'Out for Delivery')
@section('page-description', 'Orders out for delivery')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Out for Delivery</h3>
            <p class="text-sm text-gray-500 mt-1">Orders in transit</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Address</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-301</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rajesh Kumar</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹12,450</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Andheri West, Mumbai</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Driver-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Out for Delivery</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Track</a><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-302</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Priya Sharma</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,900</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gurgaon, Delhi</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Driver-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Out for Delivery</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Track</a><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr></tbody></table></div></div>
</div>
@endsection
