@extends('layouts.app')

@section('title', 'POS Report')
@section('page-title', 'POS Reports')
@section('page-description', 'View POS sales reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">POS Report</h3>
            <p class="text-sm text-gray-500 mt-1">POS analytics</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Transaction</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Growth</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">45</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹2,45,680</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹5,459</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">+12.5%</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Export</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delhi NCR</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">38</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,89,400</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹4,984</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="text-green-600">+8.3%</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Export</a></td></tr></tbody></table></div></div>
</div>
@endsection
