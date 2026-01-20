@extends('layouts.app')

@section('title', 'Order History')
@section('page-title', 'Order Status History')
@section('page-description', 'View order status changes')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Order History</h3>
            <p class="text-sm text-gray-500 mt-1">Status changes</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Changed By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rajesh Kumar</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Preparing</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Out for Delivery</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Store Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 14:30:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Priya Sharma</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Out for Delivery</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Completed</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delivery Agent</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 16:45:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Amit Patel</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">New</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Preparing</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin User</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 11:20:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-004</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Sneha Reddy</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Preparing</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Cancelled</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Customer</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19 15:10:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ORD-2024-005</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Vikram Singh</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">New</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Accepted</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Store Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19 10:05:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
