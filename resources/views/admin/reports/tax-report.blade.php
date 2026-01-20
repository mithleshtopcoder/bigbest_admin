@extends('layouts.app')

@section('title', 'Tax Report')
@section('page-title', 'Tax Reports')
@section('page-description', 'View tax reports')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Tax Report</h3>
            <p class="text-sm text-gray-500 mt-1">Tax analytics</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Total GST</p>
                    <p class="text-2xl font-semibold text-gray-900">₹44,222</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">CGST</p>
                    <p class="text-2xl font-semibold text-gray-900">₹22,111</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">SGST</p>
                    <p class="text-2xl font-semibold text-gray-900">₹22,111</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Taxable Amount</p>
                    <p class="text-2xl font-semibold text-gray-900">₹2,45,680</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taxable Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CGST</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SGST</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tax</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">INV-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹45,680</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹4,111</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹4,111</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹8,222</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">INV-002</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹32,450</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹2,921</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹2,921</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹5,841</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
