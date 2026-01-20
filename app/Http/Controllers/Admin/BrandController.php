<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Helpers\MyHelper;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get();

        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255|unique:brands,name',
        'slug'        => 'nullable|string|max:255|unique:brands,slug',
        'description' => 'nullable|string',
        'website'     => 'nullable|url',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'sort_order'  => 'nullable|integer',
        'is_active'   => 'required|boolean',
    ]);

    $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
    $validated['is_active'] = $request->input('is_active', 0);

    if ($request->hasFile('image')) {
        $validated['image'] = MyHelper::uploadImage(
            $request->file('image'),
            'brands'
        );
    }

    if ($request->hasFile('logo')) {
        $validated['logo'] = MyHelper::uploadImage(
            $request->file('logo'),
            'brands'
        );
    }

    Brand::create($validated);

    return redirect()->route('manage-product.brands.index')
        ->with('success', 'Brand created successfully');
}

public function datatable()
    {
        $brands = Brand::query();

        return DataTables::of($brands)
            ->addColumn('id', function($brand) {
                return '#BRD-' . str_pad($brand->id, 3, '0', STR_PAD_LEFT);
            })
            ->addColumn('name', function($brand) {
                return $brand->name;
            })
            ->addColumn('website', function($brand) {
                return $brand->website ?? '-';
            })
            ->addColumn('status', function($brand) {
                return $brand->is_active
                    ? '<span class="badge bg-soft-success text-success">Active</span>'
                    : '<span class="badge bg-soft-danger text-danger">Inactive</span>';
            })
            ->addColumn('sort_order', function($brand) {
                return $brand->sort_order;
            })
            ->addColumn('actions', function($brand) {
                $editUrl = route('manage-product.brands.edit', $brand->id);
                $deleteUrl = route('manage-product.brands.destroy', $brand->id);

                return '
                    <div class="d-flex justify-content-end gap-2">
                        <a href="'.$editUrl.'" class="btn btn-sm btn-link text-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="'.$deleteUrl.'" method="POST" onsubmit="return confirm(\'Delete this brand?\')">
                            '.csrf_field().method_field('DELETE').'
                            <button class="btn btn-sm btn-link text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

 public function update(Request $request, $id)
{
    $brand = Brand::findOrFail($id);

    $validated = $request->validate([
        'name'        => 'required|string|max:255|unique:brands,name,' . $brand->id,
        'slug'        => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
        'description' => 'nullable|string',
        'website'     => 'nullable|url',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'sort_order'  => 'nullable|integer',
        'is_active'   => 'required|boolean',
    ]);

    $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
    $validated['is_active'] = $request->input('is_active', 0);

    // ✅ JUST UPDATE IMAGE PATH
    if ($request->hasFile('image')) {
        $validated['image'] = MyHelper::uploadImage(
            $request->file('image'),
            'brands'
        );
    }

    if ($request->hasFile('logo')) {
        $validated['logo'] = MyHelper::uploadImage(
            $request->file('logo'),
            'brands'
        );
    }

    $brand->update($validated);

    return redirect()->route('manage-product.brands.index')
        ->with('success', 'Brand updated successfully');
}



    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }

        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('manage-product.brands.index')->with('success', 'Brand deleted successfully');
    }
}