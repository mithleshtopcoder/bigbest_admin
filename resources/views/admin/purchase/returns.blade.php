@extends('layouts.app')

@section('title', 'Purchase Returns')
@section('page-title', 'Purchase Returns')
@section('page-description', 'Manage purchase returns')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Purchase Returns</h3>
            <p class="text-sm text-gray-500 mt-1">Return to vendors</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return No</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RET-2024-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Suppliers Ltd</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹45,600</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-18</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Defective Items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RET-2024-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Silver Traders Inc</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1 item</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹12,300</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Wrong Product</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Approved</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr></tbody></table></div></div>
</div>
@endsection
