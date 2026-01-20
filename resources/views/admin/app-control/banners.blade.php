@extends('layouts.app')

@section('title', 'Banners')
@section('page-title', 'App Banner Management')
@section('page-description', 'Manage mobile app banners')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Banners</h3>
            <p class="text-sm text-gray-500 mt-1">App banners</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">App Banners</h4>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add Banner</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="h-40 bg-gradient-to-r from-yellow-400 to-yellow-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">New Year Sale</span>
                    </div>
                    <div class="p-4">
                        <h5 class="font-semibold text-gray-900 mb-1">Banner 1</h5>
                        <p class="text-sm text-gray-500 mb-2">Home Page - Top</p>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <div class="flex space-x-2">
                                <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                                <button class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="h-40 bg-gradient-to-r from-pink-400 to-pink-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">Valentine's Special</span>
                    </div>
                    <div class="p-4">
                        <h5 class="font-semibold text-gray-900 mb-1">Banner 2</h5>
                        <p class="text-sm text-gray-500 mb-2">Home Page - Middle</p>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            <div class="flex space-x-2">
                                <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                                <button class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="h-40 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                        <span class="text-white font-semibold text-lg">Flash Sale</span>
                    </div>
                    <div class="p-4">
                        <h5 class="font-semibold text-gray-900 mb-1">Banner 3</h5>
                        <p class="text-sm text-gray-500 mb-2">Category Page</p>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            <div class="flex space-x-2">
                                <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                                <button class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
