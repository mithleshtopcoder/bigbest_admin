@extends('layouts.app')

@section('title', 'Store Status')
@section('page-title', 'Store Status Management')
@section('page-description', 'Manage online/offline status of stores')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Store Status</h3>
            <p class="text-sm text-gray-500 mt-1">Control online/offline status for stores</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Online</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 09:00 AM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button class="text-indigo-600 hover:text-indigo-900">Toggle</button></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Online</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 10:00 AM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button class="text-indigo-600 hover:text-indigo-900">Toggle</button></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Andheri</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Offline</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19 18:00 PM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button class="text-indigo-600 hover:text-indigo-900">Toggle</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

