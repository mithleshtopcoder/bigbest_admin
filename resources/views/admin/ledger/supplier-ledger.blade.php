@extends('layouts.app')

@section('title', 'Supplier Ledger')
@section('page-title', 'Supplier Ledger')
@section('page-description', 'Supplier account statements')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Supplier Ledger</h3>
            <p class="text-sm text-gray-500 mt-1">Track supplier payments and outstanding</p>
        </div>
        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
            <option>All Suppliers</option>
            <option>Gold Suppliers Pvt Ltd</option>
            <option>Silver Traders Inc</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Suppliers Pvt Ltd</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Purchase</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">₹2,45,680</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹2,45,680</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-14</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Suppliers Pvt Ltd</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Payment</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">-</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">₹1,00,000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹1,45,680</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

