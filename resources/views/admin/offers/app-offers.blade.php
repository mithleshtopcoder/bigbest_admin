@extends('layouts.app')

@section('title', 'App Offers')
@section('page-title', 'App-Only Offers')
@section('page-description', 'Exclusive mobile app offers')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">App Offers</h3>
            <p class="text-sm text-gray-500 mt-1">Mobile app exclusive</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">App-Only Offers</h4>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>New Offer</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offer Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid From</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">New Year Special</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">50% Off</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-01</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-31</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-red-600 hover:text-red-900">Delete</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">First Order Bonus</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹500 Off</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-01</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-12-31</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-red-600 hover:text-red-900">Delete</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Weekend Sale</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">30% Off</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-21</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Expired</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a><a href="#" class="text-red-600 hover:text-red-900">Delete</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
