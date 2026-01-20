@extends('layouts.app')

@section('title', 'Stock Adjustment')
@section('page-title', 'Stock Adjustment Logs')
@section('page-description', 'View stock adjustment history')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Stock Adjustment</h3>
            <p class="text-sm text-gray-500 mt-1">Adjustment history</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adjustment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adjusted By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADJ-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Ring 22K</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Decrease</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-5</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Damaged items</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Store Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-20</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADJ-002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Necklace 18K</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Increase</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+10</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Stock found</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Inventory Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-19</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADJ-003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Silver Bracelet</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Andheri</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Decrease</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-3</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Theft/Loss</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Store Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-18</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADJ-004</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Gold Earrings 22K</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Increase</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">+8</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Return from customer</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cashier</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-17</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">ADJ-005</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Diamond Ring</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Decrease</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-2</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Quality issue</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Quality Manager</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-16</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
