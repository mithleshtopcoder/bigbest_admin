@extends('layouts.app')

@section('title', 'Inter-store Transfers')
@section('page-title', 'Inter-store Transfers')
@section('page-description', 'Manage stock transfers between stores')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Stock Transfers</h3>
            <p class="text-sm text-gray-500 mt-1">Track and manage inter-store stock movements</p>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>New Transfer</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transfer ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TRF-2024-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delhi NCR</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5 items</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                            <button class="text-gray-600 hover:text-gray-900">Print</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">TRF-2024-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bangalore</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Chennai</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3 items</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-14</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                In Transit
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                            <button class="text-gray-600 hover:text-gray-900">Track</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

