@extends('layouts.app')

@section('title', 'Loyalty Points')
@section('page-title', 'Loyalty Points')
@section('page-description', 'Manage customer loyalty points')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Loyalty Points</h3>
            <p class="text-sm text-gray-500 mt-1">Customer rewards</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">LP-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+250</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Earned</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Order Purchase</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1,250</td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">LP-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-100</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Redeemed</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-18</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Discount Applied</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1,000</td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">LP-003</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+150</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Earned</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-15</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Order Purchase</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1,100</td></tr></tbody></table></div></div>
</div>
@endsection
