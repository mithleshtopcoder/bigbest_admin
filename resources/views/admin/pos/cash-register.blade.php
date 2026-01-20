@extends('layouts.app')

@section('title', 'Cash Register')
@section('page-title', 'Cash Register')
@section('page-description', 'Open and close cash register')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Cash Register</h3>
            <p class="text-sm text-gray-500 mt-1">Manage cash register</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Opening Balance</p>
                    <p class="text-2xl font-semibold text-gray-900">₹50,000</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Cash Sales</p>
                    <p class="text-2xl font-semibold text-gray-900">₹1,25,680</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Expected Closing</p>
                    <p class="text-2xl font-semibold text-gray-900">₹1,75,680</p>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Register Status</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Store</span>
                        <span class="text-sm font-medium text-gray-900">Mumbai Central</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Status</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Open</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Opened By</span>
                        <span class="text-sm font-medium text-gray-900">John Doe</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">Opened At</span>
                        <span class="text-sm font-medium text-gray-900">2024-01-20 09:00 AM</span>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3">
                    <button class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Close Register</button>
                    <button class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">View History</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
