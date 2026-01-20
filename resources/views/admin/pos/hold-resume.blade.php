@extends('layouts.app')

@section('title', 'Hold / Resume Bill')
@section('page-title', 'Hold & Resume Bills')
@section('page-description', 'Manage held bills')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Hold / Resume Bills</h3>
            <p class="text-sm text-gray-500 mt-1">Manage bills on hold</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Held By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Held At</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">BILL-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rajesh Kumar</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3 items</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹45,680</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">John Doe</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 14:30</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Resume</a><a href="#" class="text-red-600 hover:text-red-900">Cancel</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">BILL-002</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Priya Sharma</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2 items</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹32,450</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Smith</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 13:15</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Resume</a><a href="#" class="text-red-600 hover:text-red-900">Cancel</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
