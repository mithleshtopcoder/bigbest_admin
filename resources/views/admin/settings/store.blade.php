@extends('layouts.app')

@section('title', 'Store Settings')
@section('page-title', 'Store Settings')
@section('page-description', 'General store settings')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Store Settings</h3>
            <p class="text-sm text-gray-500 mt-1">Store configuration</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Store</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>Mumbai Central</option>
                            <option>Bandra</option>
                            <option>Andheri</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Zone</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>Asia/Kolkata (IST)</option>
                            <option>Asia/Dubai (GST)</option>
                            <option>America/New_York (EST)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>DD/MM/YYYY</option>
                            <option>MM/DD/YYYY</option>
                            <option>YYYY-MM-DD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Format</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected>12 Hour</option>
                            <option>24 Hour</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Enable Multi-Store Management</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Enable Stock Transfer Between Stores</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Require Approval for Stock Transfers</span>
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
