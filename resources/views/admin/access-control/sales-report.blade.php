@extends('layouts.app')

@section('title', 'Sales Report')
@section('page-title', 'Employee Sales Report')
@section('page-description', 'View employee sales performance')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Sales Report</h3>
            <p class="text-sm text-gray-500 mt-1">Employee performance</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Order Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">John Doe</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹2,45,680</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">45</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹5,459</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jan 2024</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Smith</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,89,450</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">32</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹5,920</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jan 2024</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mike Johnson</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Andheri</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,56,200</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">28</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹5,579</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jan 2024</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
