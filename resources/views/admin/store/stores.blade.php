@extends('layouts.app')

@section('title', 'Stores')
@section('page-title', 'Store Management')
@section('page-description', 'Manage all store locations')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">All Stores</h3>
            <p class="text-sm text-gray-500 mt-1">Manage store locations and settings</p>
        </div>
        <a href="{{ route('admin.store.manage-store.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Store</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">STORE-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Mumbai, Maharashtra</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+91 98765 43210</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">STORE-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Mumbai, Maharashtra</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+91 98765 43211</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">STORE-003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Andheri</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Mumbai, Maharashtra</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+91 98765 43212</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Closed</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

