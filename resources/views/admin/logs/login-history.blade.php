@extends('layouts.app')

@section('title', 'Login History')
@section('page-title', 'Login History')
@section('page-description', 'View user login records')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Login History</h3>
            <p class="text-sm text-gray-500 mt-1">Login records</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Login Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin User</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">admin@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.100</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Chrome on Windows</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Success</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 10:30:45</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">manager@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.101</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Safari on macOS</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Success</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 09:15:22</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cashier</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">cashier@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.102</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Firefox on Windows</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Failed</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 08:45:10</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Store Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">store.manager@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.103</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Chrome on Android</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Success</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19 18:20:33</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Admin User</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">admin@example.com</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">192.168.1.100</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Chrome on Windows</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Success</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19 14:12:08</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
