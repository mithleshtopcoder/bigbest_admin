@extends('layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Supplier Management')
@section('page-description', 'Manage supplier master data')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">All Suppliers</h3>
            <p class="text-sm text-gray-500 mt-1">Manage supplier information and contacts</p>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Supplier</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900">Gold Suppliers Pvt Ltd</h4>
                    <p class="text-sm text-gray-500 mt-1">Supplier ID: SUP-001</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Active
                </span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    contact@goldsuppliers.com
                </div>
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    +91 98765 43210
                </div>
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-xs text-gray-500">Total Orders: 45</p>
                    <p class="text-xs text-gray-500">Outstanding: ₹2,45,680</p>
                </div>
            </div>
            <div class="mt-4 flex space-x-2">
                <button class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200">
                    Edit
                </button>
                <button class="flex-1 bg-indigo-50 text-indigo-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-indigo-100">
                    View Ledger
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900">Silver Traders Inc</h4>
                    <p class="text-sm text-gray-500 mt-1">Supplier ID: SUP-002</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Active
                </span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    info@silvertraders.com
                </div>
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    +91 98765 43211
                </div>
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-xs text-gray-500">Total Orders: 32</p>
                    <p class="text-xs text-gray-500">Outstanding: ₹1,89,400</p>
                </div>
            </div>
            <div class="mt-4 flex space-x-2">
                <button class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200">
                    Edit
                </button>
                <button class="flex-1 bg-indigo-50 text-indigo-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-indigo-100">
                    View Ledger
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

