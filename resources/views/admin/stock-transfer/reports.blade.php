@extends('layouts.app')

@section('title', 'Transfer Reports')
@section('page-title', 'Transfer Reports')
@section('page-description', 'View transfer reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Transfer Reports</h3>
            <p class="text-sm text-gray-500 mt-1">Transfer analytics</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transfers</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items Transferred</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">January 2024</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">45 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹12,45,600</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Completed</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">December 2023</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">18</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">67 items</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹18,67,800</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Completed</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td></tr></tbody></table></div></div>
</div>
@endsection
