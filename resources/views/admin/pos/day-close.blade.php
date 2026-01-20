@extends('layouts.app')

@section('title', 'Day Close')
@section('page-title', 'Day Close (Z Report)')
@section('page-description', 'Generate end of day reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Day Close</h3>
            <p class="text-sm text-gray-500 mt-1">Close day and generate reports</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Day Close Report - January 20, 2024</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Total Sales</p>
                        <p class="text-2xl font-semibold text-gray-900">₹2,45,680</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Cash Sales</p>
                        <p class="text-2xl font-semibold text-gray-900">₹1,25,680</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Card Sales</p>
                        <p class="text-2xl font-semibold text-gray-900">₹1,20,000</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Total Transactions</p>
                        <p class="text-2xl font-semibold text-gray-900">45</p>
                    </div>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-6">
                <h5 class="font-semibold text-gray-900 mb-4">Payment Summary</h5>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Cash</span>
                        <span class="text-gray-900 font-medium">₹1,25,680</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Card</span>
                        <span class="text-gray-900 font-medium">₹1,20,000</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Returns</span>
                        <span class="text-red-600 font-medium">-₹12,500</span>
                    </div>
                    <div class="border-t border-gray-200 pt-2 flex justify-between font-semibold">
                        <span>Net Total</span>
                        <span>₹2,33,180</span>
                    </div>
                </div>
                <div class="mt-6">
                    <button class="w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700">Close Day & Generate Report</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
