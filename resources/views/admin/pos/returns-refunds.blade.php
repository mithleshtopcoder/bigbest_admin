@extends('layouts.app')

@section('title', 'Returns & Refunds')
@section('page-title', 'Returns & Refunds')
@section('page-description', 'Process returns and refunds')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Returns & Refunds</h3>
            <p class="text-sm text-gray-500 mt-1">Handle customer returns</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refund Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RET-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS-2024-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rajesh Kumar</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Gold Ring 22K</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹45,680</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Processed</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RET-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS-2024-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Priya Sharma</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Silver Bracelet</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹12,450</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
