@extends('layouts.app')

@section('title', 'Service Radius')
@section('page-title', 'Store Service Radius')
@section('page-description', 'Configure delivery/service radius for each store')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Service Radius Configuration</h3>
            <p class="text-sm text-gray-500 mt-1">Set delivery and service radius for stores</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h5 class="font-semibold text-gray-900">Mumbai Central</h5>
                        <p class="text-sm text-gray-500">Current radius: 15 km</p>
                    </div>
                    <input type="number" value="15" placeholder="Radius in km" class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h5 class="font-semibold text-gray-900">Bandra</h5>
                        <p class="text-sm text-gray-500">Current radius: 12 km</p>
                    </div>
                    <input type="number" value="12" placeholder="Radius in km" class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h5 class="font-semibold text-gray-900">Andheri</h5>
                        <p class="text-sm text-gray-500">Current radius: 10 km</p>
                    </div>
                    <input type="number" value="10" placeholder="Radius in km" class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

