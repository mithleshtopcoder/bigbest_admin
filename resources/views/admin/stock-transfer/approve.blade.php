@extends('layouts.app')

@section('title', 'Transfer Requests')
@section('page-title', 'Transfer Requests')
@section('page-description', 'Approve or reject inter-store transfer requests')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Transfer Requests</h3>
            <p class="text-sm text-gray-500 mt-1">Review and approve stock transfer requests</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">REQ-2024-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Delhi NCR</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rajesh Kumar</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Pending Approval
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                            <button class="text-red-600 hover:text-red-900 mr-3">Reject</button>
                            <button class="text-indigo-600 hover:text-indigo-900">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

