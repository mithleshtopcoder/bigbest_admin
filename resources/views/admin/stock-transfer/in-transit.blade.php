@extends('layouts.app')

@section('title', 'In-Transit Stock')
@section('page-title', 'In-Transit Stock')
@section('page-description', 'View stock in transit')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">In-Transit Stock</h3>
            <p class="text-sm text-gray-500 mt-1">Stock in transit</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transfer ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Store</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To Store</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Date</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TRF-2024-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delhi NCR</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">5 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">In Transit</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-22</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Track</a><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TRF-2024-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delhi NCR</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bangalore East</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">In Transit</span></td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-23</td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Track</a><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr></tbody></table></div></div>
</div>
@endsection
