@extends('layouts.app')

@section('title', 'Order Radius')
@section('page-title', 'Order Radius Settings')
@section('page-description', 'Configure order delivery radius')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Order Radius</h3>
            <p class="text-sm text-gray-500 mt-1">Delivery radius</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Delivery Radius (km)</label>
                        <input type="number" value="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Default radius for all stores</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Delivery Radius (km)</label>
                        <input type="number" value="25" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Maximum allowed delivery distance</p>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Store-wise Radius Settings</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="font-semibold text-gray-900">Mumbai Central</h5>
                                <p class="text-sm text-gray-500">Current radius: 15 km</p>
                            </div>
                            <input type="number" value="15" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="font-semibold text-gray-900">Bandra</h5>
                                <p class="text-sm text-gray-500">Current radius: 12 km</p>
                            </div>
                            <input type="number" value="12" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h5 class="font-semibold text-gray-900">Andheri</h5>
                                <p class="text-sm text-gray-500">Current radius: 10 km</p>
                            </div>
                            <input type="number" value="10" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
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
