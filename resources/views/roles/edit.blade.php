@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Role</h2>
            <p class="text-sm text-gray-500">Update role & assign permissions</p>
        </div>

        <a href="{{ route('roles.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('roles.update', $role->id) }}">
        @csrf
        @method('PUT')

        <!-- Role Name -->
        <div class="bg-white border rounded shadow p-6 mb-6">
            <label class="block text-sm font-medium mb-1">
                Role Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="w-full rounded border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <!-- Permission Controls -->
        <div class="bg-white border rounded shadow p-6 mb-6">
            <div class="flex flex-wrap justify-between items-center gap-4 mb-4">

                <!-- Search -->
                <input type="text" id="permissionSearch" placeholder="Search permissions..." class="w-full md:w-1/3 rounded border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                <!-- Global Select -->
                <label class="flex items-center gap-2 font-medium cursor-pointer">
                    <input type="checkbox" id="selectAllPermissions" class="h-4 w-4 text-indigo-600">
                    Select All Permissions
                </label>
            </div>

            <!-- Permission Categories -->
            @foreach($categories as $category)
            <div class="border rounded mb-4 permission-category">

                <!-- Category Header -->
                <div class="flex justify-between items-center bg-gray-100 px-4 py-3 cursor-pointer category-toggle">
                    <h4 class="font-semibold text-gray-800">
                        {{ $category->name }}
                    </h4>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" class="category-select-all h-4 w-4 text-indigo-600">
                        Select All
                    </label>
                </div>

                <!-- Category Permissions -->
                <div class="category-body hidden p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($category->activePermissions as $permission)
                        <label class="flex items-center gap-2 p-2 border rounded hover:bg-gray-50 permission-item">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox h-4 w-4 text-indigo-600" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">
                                {{ $permission->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <!-- Submit Bar (Sticky) -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow px-6 py-4 flex justify-end gap-3">
            <a href="{{ route('roles.index') }}" class="px-5 py-2 border rounded hover:bg-gray-100">
                Cancel
            </a>

            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Update Role
            </button>
        </div>

    </form>
</div>

<!-- JS -->
<script>
    // Toggle category
    document.querySelectorAll('.category-toggle').forEach(header => {
        header.addEventListener('click', () => {
            header.nextElementSibling.classList.toggle('hidden');
        });
    });

    // Category select all
    document.querySelectorAll('.category-select-all').forEach(selectAll => {
        selectAll.addEventListener('change', function() {
            const checkboxes = this.closest('.permission-category')
                .querySelectorAll('.permission-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    });

    // Global select all
    document.getElementById('selectAllPermissions').addEventListener('change', function() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        document.querySelectorAll('.category-select-all').forEach(cat => {
            cat.checked = this.checked;
        });
    });

    // Search permissions
    document.getElementById('permissionSearch').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        document.querySelectorAll('.permission-item').forEach(item => {
            item.style.display = item.innerText.toLowerCase().includes(value) ?
                'flex' :
                'none';
        });
    });

</script>

@endsection
