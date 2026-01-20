<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\MyHelper;
use Yajra\DataTables\Facades\DataTables;


class SubCategoryController extends Controller
{
    /**
     * List sub categories
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')
            ->latest()
            ->paginate(10);

        return view('manage-product.sub-category.index', compact('subCategories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();

        return view('manage-product.sub-category.create', compact('categories'));
    }

    /**
     * Store sub category
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon'        => 'nullable|string|max:255',
            'label'       => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = $request->only([
            'category_id',
            'name',
            'description',
            'icon',
            'label',
            'sort_order',
            'is_active',
        ]);

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
        $data['image'] = MyHelper::uploadImage(
            $request->file('image'),
            'subcategories'
        );
    }

        SubCategory::create($data);

        return redirect()
            ->route('manage-product.sub-category.index')
            ->with('success', 'Sub Category created successfully');
    }


    
public function datatable()
{
    $subCategories = \App\Models\SubCategory::with('category');

    return DataTables::of($subCategories)
        ->addColumn('id', function($row) {
            return '#SUB-' . str_pad($row->id, 3, '0', STR_PAD_LEFT);
        })
        ->addColumn('name', function($row) {
            return '<div class="d-flex align-items-center gap-2">
                        <i class="bi bi-diagram-3 text-primary"></i>
                        <strong>'.$row->name.'</strong>
                    </div>';
        })
        ->addColumn('parent_category', function($row) {
            return $row->category->name ?? 'N/A';
        })
        ->addColumn('status', function($row) {
            return $row->is_active
                ? '<span class="badge bg-soft-success text-success">Active</span>'
                : '<span class="badge bg-soft-danger text-danger">Inactive</span>';
        })
        ->addColumn('actions', function($row) {
            $editUrl = route('manage-product.sub-category.edit', $row->id);
            $deleteUrl = route('manage-product.sub-category.destroy', $row->id);
            return '
                <div class="d-flex justify-content-end gap-2">
                    <a href="'.$editUrl.'" class="btn btn-sm btn-link text-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="'.$deleteUrl.'" method="POST" onsubmit="return confirm(\'Delete this sub category?\')">
                        '.csrf_field().method_field('DELETE').'
                        <button class="btn btn-sm btn-link text-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            ';
        })
        ->rawColumns(['name','status','actions'])
        ->make(true);
}
    /**
     * Edit sub category
     */
    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories  = Category::where('is_active', 1)->get();

        return view('manage-product.sub-category.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update sub category
     */
    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon'        => 'nullable|string|max:255',
            'label'       => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = $request->only([
            'category_id',
            'name',
            'description',
            'icon',
            'label',
            'sort_order',
            'is_active',
        ]);

        if ($subCategory->name !== $request->name) {
            $data['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('image')) {
        $data['image'] = MyHelper::uploadImage(
            $request->file('image'),
            'subcategories'
        );
    }

        $subCategory->update($data);

        return redirect()
            ->route('manage-product.sub-category.index')
            ->with('success', 'Sub Category updated successfully');
    }

    /**
     * Delete sub category
     */
    public function destroy($id)
    {
        SubCategory::findOrFail($id)->delete();

        return redirect()
            ->route('manage-product.sub-category.index')
            ->with('success', 'Sub Category deleted successfully');
    }
}