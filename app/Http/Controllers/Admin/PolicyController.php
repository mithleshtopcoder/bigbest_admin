<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display a listing of the policies
     */
    public function index()
    {
        $policies = Policy::orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get();

        return view('policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new policy
     */
    public function create()
    {
        return view('policies.create');
    }

    /**
     * Store a newly created policy
     */
    public function store(Request $request)
{
    // Convert checkbox value
    $request->merge([
        'is_active' => $request->has('is_active') ? 1 : 0,
    ]);

    $validated = $request->validate([
        'type'       => 'required|string|max:100',
        'title'      => 'nullable|string|max:255',
        'content'    => 'required|string',
        'is_active'  => 'boolean',
        'sort_order' => 'nullable|integer',
    ]);

    Policy::create($validated);

    return redirect()
        ->route('policies.index')
        ->with('success', 'Policy created successfully');
}


    /**
     * Show the form for editing the specified policy
     */
    public function edit(string $id)
    {
        $policy = Policy::findOrFail($id);

        return view('policies.edit', compact('policy'));
    }

    /**
     * Update the specified policy
     */
   public function update(Request $request, string $id)
{
    $policy = Policy::findOrFail($id);

    $validated = $request->validate([
        'type'       => 'required|string|max:100',
        'title'      => 'nullable|string|max:255',
        'content'    => 'required|string',
        'is_active'  => 'required|boolean',
        'sort_order' => 'nullable|integer',
    ]);

    $policy->update($validated);

    return redirect()
        ->route('policies.index')
        ->with('success', 'Policy updated successfully');
}


    /**
     * Remove the specified policy (Soft Delete)
     */
    public function destroy(string $id)
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();

        return redirect()
            ->route('policies.index')
            ->with('success', 'Policy deleted successfully');
    }
}