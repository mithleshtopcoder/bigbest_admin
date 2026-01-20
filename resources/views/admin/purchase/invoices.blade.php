@extends('layouts.app')

@section('title', 'Purchase Invoices')
@section('page-title', 'Purchase Invoices')
@section('page-description', 'View purchase invoices')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Purchase Invoices</h3>
            <p class="text-sm text-gray-500 mt-1">Vendor invoices</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">INV-2024-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Suppliers Ltd</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹5,45,600</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-15</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PO-2024-001</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Paid</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Pay</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">INV-2024-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Silver Traders Inc</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹2,34,500</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-16</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">PO-2024-002</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Pay</a></td></tr></tbody></table></div></div>
</div>
@endsection
