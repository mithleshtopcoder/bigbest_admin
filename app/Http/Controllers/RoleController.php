<?php


namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display list of roles
     */
    public function index()
    {
        $roles = Role::where('is_active', true)->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show create role form
     */
    public function create()
    {
        $categories = PermissionCategory::with('activePermissions')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('roles.create', compact('categories'));
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => [
                'integer',
                Rule::exists('permissions', 'id')
                    ->where('guard_name', 'web')
                    ->whereNull('deleted_at'),
            ],
        ]);

        $role = Role::create([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'guard_name' => 'web',
            'is_active'  => true,
        ]);

        // Attach permissions
        $permissionIds = collect($request->input('permissions', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->whereIn('id', $permissionIds)
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Show edit role form
     */
    public function edit(Role $role)
    {
        $categories = PermissionCategory::with('activePermissions')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact(
            'role',
            'categories',
            'rolePermissions'
        ));
    }

    /**
     * Update role
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => [
                'integer',
                Rule::exists('permissions', 'id')
                    ->where('guard_name', 'web')
                    ->whereNull('deleted_at'),
            ],
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // Sync permissions
        $permissionIds = collect($request->input('permissions', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->whereIn('id', $permissionIds)
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Delete role (soft delete)
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}