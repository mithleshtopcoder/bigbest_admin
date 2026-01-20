@extends('layouts.app')

@section('title', 'Customer Ledger')
@section('page-title', 'Customer Ledger')
@section('page-description', 'Customer account statements')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Customer Ledger</h3>
            <p class="text-sm text-gray-500 mt-1">Track customer payments and receivables</p>
        </div>
        <input type="text" placeholder="Search customer..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Amit Patel</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sales</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">₹12,450</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹12,450</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

