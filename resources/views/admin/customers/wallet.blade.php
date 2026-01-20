@extends('layouts.app')

@section('title', 'Wallet')
@section('page-title', 'Wallet / Credits')
@section('page-description', 'Manage customer wallet and credits')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Wallet & Credits</h3>
            <p class="text-sm text-gray-500 mt-1">Customer wallet balance</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TXN-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Credit</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Reward Points Conversion</td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TXN-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Debit</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-₹200</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,000</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-18</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Order Payment</td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TXN-003</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Credit</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,000</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,200</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-15</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Wallet Top-up</td></tr></tbody></table></div></div>
</div>
@endsection
