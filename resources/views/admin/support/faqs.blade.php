@extends('layouts.app')

@section('title', 'FAQs')
@section('page-title', 'FAQs')
@section('page-description', 'Manage frequently asked questions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">FAQs</h3>
            <p class="text-sm text-gray-500 mt-1">Help center</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">Frequently Asked Questions</h4>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add FAQ</span>
                </button>
            </div>
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-2">How do I place an order?</h5>
                            <p class="text-sm text-gray-600">You can place an order through our mobile app or website. Simply browse products, add them to cart, and proceed to checkout.</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-2">What are the delivery charges?</h5>
                            <p class="text-sm text-gray-600">Delivery charges vary based on your location and order value. Free delivery is available for orders above ₹500.</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-2">How can I track my order?</h5>
                            <p class="text-sm text-gray-600">Once your order is confirmed, you'll receive a tracking number via SMS and email. You can track your order in real-time through the app.</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-2">What is the return policy?</h5>
                            <p class="text-sm text-gray-600">We offer a 7-day return policy for unused items in original packaging. Returns can be initiated through the app or by contacting customer support.</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-900 mb-2">How do I earn loyalty points?</h5>
                            <p class="text-sm text-gray-600">You earn 1 point for every ₹10 spent. Points can be redeemed for discounts on future purchases or converted to wallet credits.</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
