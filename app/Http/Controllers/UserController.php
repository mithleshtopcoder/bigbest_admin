<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Models\Role;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'store'])->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles  = Role::where('is_active', true)->get();
        $stores = Store::where('is_online', true)->get();

        return view('admin.users.create', compact('roles', 'stores'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:users,email',
        'mobile_number' => 'nullable|string|max:20',
        'password'      => 'required|min:8|confirmed',
        'role_id'       => 'required|exists:roles,id',
        'store_id'      => 'nullable|exists:stores,id',
        'status'        => 'required|boolean',
    ]);

    $user = User::create([
        'name'          => $request->name,
        'email'         => $request->email,
        'mobile_number' => $request->mobile_number,
        'password'      => Hash::make($request->password),
        'store_id'      => $request->store_id,
        'status'        => $request->status,
    ]);

    // ✅ Assign role correctly
    $role = Role::findOrFail($request->role_id);
    $user->assignRole($role); // or syncRoles([$role->name])

    return redirect()
        ->route('users.index')
        ->with('success', 'User created successfully');
}


    public function edit(User $user)
    {
        $roles  = Role::where('is_active', true)->get();
        $stores = Store::where('is_online', true)->get();
        $userRoleId = $user->roles()->pluck('roles.id')->first();


        return view('admin.users.edit', compact('user', 'roles', 'stores','userRoleId'));
    }

    

public function update(Request $request, User $user)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:users,email,' . $user->id,
        'mobile_number' => 'nullable|string|max:20',
        'role_id'       => 'required|exists:roles,id',
        'store_id'      => 'nullable|exists:stores,id',
        'status'        => 'required|boolean',
    ]);

    // Update user fields
    $user->update([
        'name'          => $request->name,
        'email'         => $request->email,
        'mobile_number' => $request->mobile_number,
        'store_id'      => $request->store_id,
        'status'        => $request->status,
    ]);

    // ✅ Sync role properly using Role model
    $role = Role::findOrFail($request->role_id);
    $user->syncRoles([$role->name]); // or $user->assignRole($role)

    return redirect()
        ->route('users.index')
        ->with('success', 'User updated successfully');
}

    public function destroy(User $user)
    {
        $user->roles()->detach(); // optional cleanup
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}