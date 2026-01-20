@extends('layouts.app')

@section('title', 'GST Reports')
@section('page-title', 'GST Reports')
@section('page-description', 'Tax compliance and GST reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">GST Reports</h3>
            <p class="text-sm text-gray-500 mt-1">Tax compliance and filing reports</p>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Total Sales (Including GST)</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">₹45,67,890</p>
            <p class="text-sm text-gray-500 mt-2">GST Collected: ₹6,85,184</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Total Purchases (Including GST)</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">₹32,67,890</p>
            <p class="text-sm text-gray-500 mt-2">GST Paid: ₹4,90,184</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm font-medium text-gray-600">Net GST Payable</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">₹1,95,000</p>
            <p class="text-sm text-gray-500 mt-2">Due by 20th of next month</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-900">GST Summary</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">GST Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">GST Collected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purchases</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">GST Paid</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">18%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹38,00,000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹6,84,000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹27,00,000</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹4,86,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

