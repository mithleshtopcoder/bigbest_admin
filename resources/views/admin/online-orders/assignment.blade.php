@extends('layouts.app')

@section('title', 'Order Assignment')
@section('page-title', 'Order Assignment')
@section('page-description', 'Assign orders to stores/delivery')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Order Assignment</h3>
            <p class="text-sm text-gray-500 mt-1">Auto/manual order routing</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Store</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Agent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rajesh Kumar</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹12,450</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Agent-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Out for Delivery</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Reassign</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-002</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Priya Sharma</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,900</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending Assignment</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Assign</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
