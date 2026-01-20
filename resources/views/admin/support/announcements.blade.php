@extends('layouts.app')

@section('title', 'Announcements')
@section('page-title', 'System Announcements')
@section('page-description', 'Manage system announcements')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
            <p class="text-sm text-gray-500 mt-1">System messages</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">System Announcements</h4>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Announcement</span>
                </button>
            </div>
            <div class="space-y-4">
                <div class="border-l-4 border-indigo-500 bg-indigo-50 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">New Year Sale - Up to 50% Off!</h5>
                            <p class="text-sm text-gray-600 mb-2">Celebrate the new year with amazing discounts on all gold jewelry. Valid until January 31st.</p>
                            <p class="text-xs text-gray-500">Published: 2024-01-01 | Expires: 2024-01-31</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="border-l-4 border-yellow-500 bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">Maintenance Scheduled</h5>
                            <p class="text-sm text-gray-600 mb-2">System maintenance will be performed on January 25th from 2:00 AM to 4:00 AM. Services may be temporarily unavailable.</p>
                            <p class="text-xs text-gray-500">Published: 2024-01-20 | Expires: 2024-01-25</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="border-l-4 border-green-500 bg-green-50 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">New Store Opening - Bandra</h5>
                            <p class="text-sm text-gray-600 mb-2">We're excited to announce the opening of our new store in Bandra. Visit us for exclusive launch offers!</p>
                            <p class="text-xs text-gray-500">Published: 2024-01-15 | Expires: 2024-02-15</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="border-l-4 border-gray-300 bg-gray-50 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">Payment Gateway Update</h5>
                            <p class="text-sm text-gray-600 mb-2">We've updated our payment gateway for better security and faster transactions. All payment methods are now supported.</p>
                            <p class="text-xs text-gray-500">Published: 2024-01-10 | Expires: 2024-01-20</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Expired</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
