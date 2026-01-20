<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * List Banners
     */
    public function index()
    {
        $banners = Banner::orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get();

        return view('cms.banners.index', compact('banners'));
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        return view('cms.banners.create');
    }

    /**
     * Store Banner
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link'       => 'nullable|url',
            'type'       => 'required|in:home,category,product,promotional,other',
            'position'   => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        // ✅ Checkbox fix
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // ✅ Image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($validated);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner created successfully');
    }

    /**
     * Show Edit Form
     */
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('cms.banners.edit', compact('banner'));
    }

    /**
     * Update Banner
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link'       => 'nullable|url',
            'type'       => 'required|in:home,category,product,promotional,other',
            'position'   => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        // ✅ Checkbox fix
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // 🔁 Replace image safely
        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner updated successfully');
    }

    /**
     * Delete Banner (Soft Delete)
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // ❌ Do NOT delete image here (soft delete safe)
        $banner->delete();

        return redirect()
            ->route('cms.banners.index')
            ->with('success', 'Banner deleted successfully');
    }
}