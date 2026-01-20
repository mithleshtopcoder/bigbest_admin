@extends('layouts.app')

@section('title', 'New Billing')
@section('page-title', 'POS Billing')
@section('page-description', 'Create new bills at point of sale')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Billing Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Scan barcode or search product..." class="flex-1 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                        Search
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Cart Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Gold Ring 22K</div>
                                    <div class="text-sm text-gray-500">SKU: GR-22K-001</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">₹15,600</td>
                                <td class="px-6 py-4">
                                    <input type="number" value="1" min="1" class="w-20 border border-gray-300 rounded px-2 py-1 text-sm">
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">₹15,600</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-red-600 hover:text-red-900">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">Silver Bracelet</div>
                                    <div class="text-sm text-gray-500">SKU: SB-001</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">₹8,500</td>
                                <td class="px-6 py-4">
                                    <input type="number" value="1" min="1" class="w-20 border border-gray-300 rounded px-2 py-1 text-sm">
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">₹8,500</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-red-600 hover:text-red-900">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="space-y-6">
            <!-- Bill Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">₹24,100</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax (GST 5%)</span>
                        <span class="font-medium text-gray-900">₹1,205</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Discount</span>
                        <span class="font-medium text-green-600">-₹500</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-indigo-600">₹24,805</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
                <div class="space-y-3">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment" value="cash" class="mr-3" checked>
                        <span class="text-sm font-medium text-gray-900">Cash</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment" value="card" class="mr-3">
                        <span class="text-sm font-medium text-gray-900">Card</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment" value="upi" class="mr-3">
                        <span class="text-sm font-medium text-gray-900">UPI</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <button class="w-full bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                    Complete Payment
                </button>
                <button class="w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-200 font-medium">
                    Hold Bill
                </button>
                <button class="w-full bg-yellow-100 text-yellow-700 px-4 py-3 rounded-lg hover:bg-yellow-200 font-medium">
                    Print Bill
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
