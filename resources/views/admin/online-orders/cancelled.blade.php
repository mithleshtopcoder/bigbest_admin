@extends('layouts.app')

@section('title', 'Cancelled Orders')
@section('page-title', 'Cancelled Orders')
@section('page-description', 'Cancelled customer orders')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Cancelled Orders</h3>
            <p class="text-sm text-gray-500 mt-1">Cancelled orders</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancelled At</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-501</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rajesh Kumar</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹12,450</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1 hour ago</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Customer Request</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Refund</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-502</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Priya Sharma</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,900</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delhi NCR</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2 hours ago</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Out of Stock</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Refund</a></td></tr></tbody></table></div></div>
</div>
@endsection
