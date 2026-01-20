@extends('layouts.app')

@section('title', 'SMS Templates')
@section('page-title', 'SMS Templates')
@section('page-description', 'Manage SMS message templates')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">SMS Templates</h3>
            <p class="text-sm text-gray-500 mt-1">SMS templates</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">SMS Templates</h4>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Template</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Order Confirmation</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Order</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Your order {order_id} has been confirmed. Total: ₹{amount}. Thank you!</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-red-600 hover:text-red-900">Delete</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Order Shipped</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Order</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Your order {order_id} has been shipped. Track: {tracking_link}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-red-600 hover:text-red-900">Delete</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">OTP Verification</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Auth</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Your OTP for Gold Forming is {otp}. Valid for 5 minutes.</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-red-600 hover:text-red-900">Delete</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Payment Reminder</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Payment</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Reminder: Payment of ₹{amount} for order {order_id} is pending. Pay now: {payment_link}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-red-600 hover:text-red-900">Delete</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
