@extends('layouts.app')

@section('title', 'Promotional Broadcasts')
@section('page-title', 'Promotional Broadcasts')
@section('page-description', 'Send promotional messages')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Promotional Broadcasts</h3>
            <p class="text-sm text-gray-500 mt-1">Marketing messages</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">Promotional Broadcasts</h4>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Broadcast</span>
                </button>
            </div>
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">New Year Sale - 50% Off!</h5>
                            <p class="text-sm text-gray-600 mb-2">Celebrate the new year with amazing discounts on all gold jewelry. Valid until January 31st.</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500 mb-2">
                                <span>Sent: 2024-01-01 10:00 AM</span>
                                <span>Channel: Email, SMS, Push</span>
                            </div>
                            <div class="flex items-center space-x-4 text-xs">
                                <span class="text-gray-600">Recipients: <span class="font-semibold">2,500</span></span>
                                <span class="text-gray-600">Opened: <span class="font-semibold">1,856 (74.2%)</span></span>
                                <span class="text-gray-600">Clicked: <span class="font-semibold">892 (35.7%)</span></span>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-4">Completed</span>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">Valentine's Day Special</h5>
                            <p class="text-sm text-gray-600 mb-2">Express your love with our exclusive Valentine's Day collection. Special prices on couple rings and pendants.</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500 mb-2">
                                <span>Scheduled: 2024-02-10 09:00 AM</span>
                                <span>Channel: Email, Push</span>
                            </div>
                            <div class="flex items-center space-x-4 text-xs">
                                <span class="text-gray-600">Target: <span class="font-semibold">All Customers</span></span>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ml-4">Scheduled</span>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-1">Flash Sale - Today Only!</h5>
                            <p class="text-sm text-gray-600 mb-2">Limited time offer! Get 30% off on selected items. Use code FLASH30 at checkout.</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500 mb-2">
                                <span>Sent: 2024-01-15 12:00 PM</span>
                                <span>Channel: SMS, Push</span>
                            </div>
                            <div class="flex items-center space-x-4 text-xs">
                                <span class="text-gray-600">Recipients: <span class="font-semibold">1,800</span></span>
                                <span class="text-gray-600">Opened: <span class="font-semibold">1,234 (68.6%)</span></span>
                                <span class="text-gray-600">Clicked: <span class="font-semibold">567 (31.5%)</span></span>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ml-4">Completed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
