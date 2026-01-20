@extends('layouts.app')

@section('title', 'Store Ledger')
@section('page-title', 'Store Ledger')
@section('page-description', 'Store-wise financial ledger')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Store Ledger</h3>
            <p class="text-sm text-gray-500 mt-1">Financial transactions by store</p>
        </div>
        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
            <option>All Stores</option>
            <option>Mumbai Central</option>
            <option>Delhi NCR</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sales</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">₹2,45,680</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹12,45,680</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delhi NCR</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Expense</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">₹5,000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹8,92,400</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

