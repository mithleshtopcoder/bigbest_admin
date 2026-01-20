@extends('layouts.app')

@section('title', 'Inventory Valuation')
@section('page-title', 'Inventory Valuation Report')
@section('page-description', 'Stock value and valuation reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Inventory Valuation</h3>
            <p class="text-sm text-gray-500 mt-1">Current stock value across all stores</p>
        </div>
        <div class="flex items-center space-x-3">
            <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option>All Stores</option>
                <option>Mumbai Central</option>
                <option>Delhi NCR</option>
            </select>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                Export
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Total Inventory Value</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">₹45,67,890</p>
            <p class="text-sm text-gray-500 mt-2">1,234 items</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Average Item Value</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">₹3,704</p>
            <p class="text-sm text-gray-500 mt-2">Per unit</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Valuation Method</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">FIFO</p>
            <p class="text-sm text-gray-500 mt-2">First In First Out</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Gold Ring 22K</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156 units</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹1,575</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹2,45,600</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

