@extends('layouts.app')

@section('title', 'POS Activity')
@section('page-title', 'POS Activity Logs')
@section('page-description', 'View POS transaction logs')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">POS Activity</h3>
            <p class="text-sm text-gray-500 mt-1">POS transactions</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS-2024-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">John Doe</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Sale</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹45,680</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cash</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 15:30:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS-2024-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Smith</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Sale</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹32,450</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Card</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 14:15:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS-2024-003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">John Doe</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Return</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-₹12,500</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cash</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 13:45:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS-2024-004</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Andheri</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mike Johnson</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Sale</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹28,900</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">UPI</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20 12:20:00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">POS-2024-005</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Smith</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Sale</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹67,200</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Card</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19 17:10:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
