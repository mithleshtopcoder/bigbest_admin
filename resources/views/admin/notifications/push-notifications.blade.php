@extends('layouts.app')

@section('title', 'Push Notifications')
@section('page-title', 'Push Notifications')
@section('page-description', 'Send push notifications')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Push Notifications</h3>
            <p class="text-sm text-gray-500 mt-1">Mobile notifications</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">Push Notifications</h4>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Send Notification</span>
                </button>
            </div>
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">New Year Sale - 50% Off!</h5>
                            <p class="text-sm text-gray-600 mb-2">Celebrate the new year with amazing discounts on all gold jewelry. Valid until January 31st.</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span>Sent: 2024-01-01 10:00 AM</span>
                                <span>Recipients: 1,245</span>
                                <span>Opened: 856 (68.7%)</span>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-4">Sent</span>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">Order Delivered</h5>
                            <p class="text-sm text-gray-600 mb-2">Your order ORD-2024-001 has been delivered successfully. Thank you for shopping with us!</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span>Sent: 2024-01-20 04:30 PM</span>
                                <span>Recipients: 1</span>
                                <span>Opened: 1 (100%)</span>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-4">Sent</span>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">New Product Launch</h5>
                            <p class="text-sm text-gray-600 mb-2">Check out our latest collection of designer gold jewelry. Limited stock available!</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span>Scheduled: 2024-01-25 09:00 AM</span>
                                <span>Recipients: 2,500</span>
                                <span>Status: Pending</span>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-4">Scheduled</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
