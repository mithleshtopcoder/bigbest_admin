@extends('layouts.app')

@section('title', 'Permissions')
@section('page-title', 'Permissions (RBAC)')
@section('page-description', 'Manage role-based permissions')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
            <p class="text-sm text-gray-500 mt-1">Access permissions</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Products</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">View</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Create</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Edit</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Delete</span>
                        </label>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Orders</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">View</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Create</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Edit</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Cancel</span>
                        </label>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">POS</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">View</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Create Bill</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Returns</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Day Close</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Permissions</button>
            </div>
        </div>
    </div>
</div>
@endsection
