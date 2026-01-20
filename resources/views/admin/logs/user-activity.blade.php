@extends('layouts.app')

@section('title', 'User Activity')
@section('page-title', 'User Activity Logs')
@section('page-description', 'View user activity logs')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">User Activity</h3>
            <p class="text-sm text-gray-500 mt-1">Activity tracking</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin User</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Created</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Products</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Created product: Gold Ring 22K</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.100</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 16:45:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Store Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Updated</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Orders</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Updated order ORD-2024-001 status</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.101</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 15:30:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Inventory Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Viewed</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Inventory</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Viewed stock report</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.102</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 14:15:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin User</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Deleted</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Suppliers</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Deleted supplier: Old Supplier Inc</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.100</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 13:00:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cashier</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Updated</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Processed POS transaction POS-2024-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.103</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 12:30:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
