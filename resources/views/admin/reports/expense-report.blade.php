@extends('layouts.app')

@section('title', 'Expense Report')
@section('page-title', 'Expense Report')
@section('page-description', 'View expense reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Expense Report</h3>
            <p class="text-sm text-gray-500 mt-1">Expense analytics</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Total Expenses</p>
                    <p class="text-2xl font-semibold text-gray-900">₹2,45,680</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">This Month</p>
                    <p class="text-2xl font-semibold text-gray-900">₹45,200</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Last Month</p>
                    <p class="text-2xl font-semibold text-gray-900">₹52,300</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Average/Month</p>
                    <p class="text-2xl font-semibold text-gray-900">₹48,750</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rent</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Store rent payment</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹25,000</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-18</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Utilities</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Electricity bill</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,500</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-15</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Salary</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Employee salaries</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,20,000</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">All Stores</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-12</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Marketing</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Advertisement campaign</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹15,000</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Head Office</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
