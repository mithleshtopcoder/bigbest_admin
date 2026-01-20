@extends('layouts.app')

@section('title', 'Barcode Billing')
@section('page-title', 'Barcode / QR Billing')
@section('page-description', 'Quick billing using barcode scanner')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Barcode Billing</h3>
            <p class="text-sm text-gray-500 mt-1">Scan and bill products</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex items-center space-x-4">
                            <input type="text" placeholder="Scan barcode or enter product code..." class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Add</button>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">Gold Ring 22K</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">1</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">₹45,680</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">₹45,680</td>
                                    <td class="px-4 py-3 text-right text-sm"><button class="text-red-600 hover:text-red-900">Remove</button></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">Silver Bracelet</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">2</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">₹12,450</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">₹24,900</td>
                                    <td class="px-4 py-3 text-right text-sm"><button class="text-red-600 hover:text-red-900">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Bill Summary</h4>
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900">₹70,580</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax (18%)</span>
                                <span class="text-gray-900">₹12,704</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount</span>
                                <span class="text-green-600">-₹1,000</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 flex justify-between font-semibold">
                                <span>Total</span>
                                <span>₹82,284</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <button class="w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700">Process Payment</button>
                            <button class="w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200">Hold Bill</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
