@extends('layouts.app')

@section('title', 'POS Settings')
@section('page-title', 'POS Settings')
@section('page-description', 'Configure POS system')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">POS Settings</h3>
            <p class="text-sm text-gray-500 mt-1">POS configuration</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Receipt Printer</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>Thermal Printer</option>
                            <option>Dot Matrix</option>
                            <option>Laser Printer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Barcode Scanner</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>USB Scanner</option>
                            <option>Bluetooth Scanner</option>
                            <option>Wireless Scanner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Payment Method</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>Cash</option>
                            <option>Card</option>
                            <option>UPI</option>
                            <option>Wallet</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Receipt Footer Text</label>
                        <input type="text" value="Thank you for your purchase!" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Enable Barcode Scanning</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Auto Print Receipt</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Require Customer Details</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Enable Hold/Resume Bills</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
