@extends('layouts.app')

@section('title', 'Store Priority')
@section('page-title', 'Store Priority Management')
@section('page-description', 'Set primary and backup store priorities')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Store Priority Settings</h3>
            <p class="text-sm text-gray-500 mt-1">Configure primary and backup store priorities</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Backup Store</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <select class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <option selected>High</option>
                                    <option>Medium</option>
                                    <option>Low</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Bandra</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <select class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <option>High</option>
                                    <option selected>Medium</option>
                                    <option>Low</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Andheri</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <select class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <option>High</option>
                                    <option>Medium</option>
                                    <option selected>Low</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mumbai Central</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

