@extends('layouts.app')

@section('title', 'Invoice Templates')
@section('page-title', 'Invoice & Receipt Templates')
@section('page-description', 'Manage invoice templates')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Invoice Templates</h3>
            <p class="text-sm text-gray-500 mt-1">Invoice design</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 cursor-pointer">
                    <div class="aspect-[3/4] bg-gray-50 rounded border-2 border-dashed border-gray-300 flex items-center justify-center mb-3">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-xs text-gray-500">Template Preview</p>
                        </div>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Default Template</h4>
                    <p class="text-sm text-gray-500 mb-3">Standard invoice layout</p>
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                        <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 cursor-pointer">
                    <div class="aspect-[3/4] bg-gray-50 rounded border-2 border-dashed border-gray-300 flex items-center justify-center mb-3">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-xs text-gray-500">Template Preview</p>
                        </div>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Modern Template</h4>
                    <p class="text-sm text-gray-500 mb-3">Contemporary design</p>
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 cursor-pointer">
                    <div class="aspect-[3/4] bg-gray-50 rounded border-2 border-dashed border-gray-300 flex items-center justify-center mb-3">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-xs text-gray-500">Template Preview</p>
                        </div>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Minimal Template</h4>
                    <p class="text-sm text-gray-500 mb-3">Simple and clean</p>
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        <button class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Create New Template</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
