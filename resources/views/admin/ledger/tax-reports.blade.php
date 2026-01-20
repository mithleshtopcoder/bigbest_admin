@extends('layouts.app')

@section('title', 'Tax Reports')
@section('page-title', 'Tax / GST Reports')
@section('page-description', 'View tax reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Tax Reports</h3>
            <p class="text-sm text-gray-500 mt-1">GST reports</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GST Collected</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GST Paid</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net GST</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th></tr></thead><tbody class="bg-white divide-y divide-gray-200"><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">January 2024</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹2,45,680</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,23,400</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,22,280</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Export</a></td></tr><tr class="hover:bg-gray-50"><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">December 2023</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹18,67,800</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹9,33,900</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹9,33,900</td><td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Filed</span></td><td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a><a href="#" class="text-indigo-600 hover:text-indigo-900">Export</a></td></tr></tbody></table></div></div>
</div>
@endsection
