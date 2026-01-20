@extends('layouts.app')

@section('title', 'Store Timings')
@section('page-title', 'Store Timings')
@section('page-description', 'Manage opening and closing hours for stores')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Store Timings</h3>
            <p class="text-sm text-gray-500 mt-1">Configure opening and closing hours for each store</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-semibold text-gray-900 mb-4">Mumbai Central</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Opening Time</label>
                            <input type="time" value="09:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Closing Time</label>
                            <input type="time" value="21:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-semibold text-gray-900 mb-4">Bandra</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Opening Time</label>
                            <input type="time" value="10:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Closing Time</label>
                            <input type="time" value="22:00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h5 class="font-semibold text-gray-900 mb-4">Andheri</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Opening Time</label>
                            <input type="time" value="09:30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Closing Time</label>
                            <input type="time" value="21:30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

