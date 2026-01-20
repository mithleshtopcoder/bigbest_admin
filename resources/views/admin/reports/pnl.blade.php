@extends('layouts.app')

@section('title', 'Profit & Loss')
@section('page-title', 'Profit & Loss Statement')
@section('page-description', 'Financial P&L analysis')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Profit & Loss Statement</h3>
            <p class="text-sm text-gray-500 mt-1">Financial performance overview</p>
        </div>
        <div class="flex items-center space-x-3">
            <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                <option>January 2024</option>
                <option>December 2023</option>
            </select>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                Export
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Income</h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Sales</span>
                    <span class="font-semibold text-gray-900">₹45,67,890</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Other Income</span>
                    <span class="font-semibold text-gray-900">₹12,450</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between">
                    <span class="font-semibold text-gray-900">Total Income</span>
                    <span class="font-bold text-lg text-gray-900">₹45,80,340</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Expenses</h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Cost of Goods Sold</span>
                    <span class="font-semibold text-gray-900">₹32,67,890</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Operating Expenses</span>
                    <span class="font-semibold text-gray-900">₹2,34,500</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Other Expenses</span>
                    <span class="font-semibold text-gray-900">₹45,000</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between">
                    <span class="font-semibold text-gray-900">Total Expenses</span>
                    <span class="font-bold text-lg text-gray-900">₹35,47,390</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm p-6 border border-green-200">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Net Profit</p>
                <p class="text-4xl font-bold text-green-700 mt-2">₹10,32,950</p>
                <p class="text-sm text-green-600 mt-2">Profit Margin: 22.5%</p>
            </div>
            <div class="w-20 h-20 bg-green-200 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection

