<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\MyHelper;
use Yajra\DataTables\Facades\DataTables;



class CategoryController extends Controller
{
    /**
     * List categories
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('manage-product.category.index', compact('categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('manage-product.category.create');
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon'        => 'nullable|string|max:255',
            'label'       => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = $request->only([
            'name',
            'description',
            'icon',
            'label',
            'sort_order',
            'is_active',
        ]);

        // Generate slug
        $data['slug'] = Str::slug($request->name);

        // Upload image
        if ($request->hasFile('image')) {
        $data['image'] = MyHelper::uploadImage(
            $request->file('image'),
            'categories'
        );
    }

        Category::create($data);

        return redirect()
            ->route('manage-product.category.index')
            ->with('success', 'Category created successfully');
    }

    public function datatable(Request $request)
    {
        $categories = Category::query();

        return DataTables::of($categories)
            ->addColumn('id', function($category) {
                return '#CAT-' . str_pad($category->id, 3, '0', STR_PAD_LEFT);
            })
            ->addColumn('name', function($category) {
                return '<div class="d-flex align-items-center gap-2">
                            <i class="bi bi-folder text-primary"></i>
                            <strong>'.$category->name.'</strong>
                        </div>';
            })
            ->addColumn('slug', function($category) {
                return $category->slug;
            })
            ->addColumn('sort_order', function($category) {
                return '<span class="badge bg-light text-dark">'.$category->sort_order.'</span>';
            })
            ->addColumn('status', function($category) {
                return $category->is_active 
                    ? '<span class="badge bg-soft-success text-success">Active</span>'
                    : '<span class="badge bg-soft-danger text-danger">Inactive</span>';
            })
            ->addColumn('actions', function($category) {
                $editUrl = route('manage-product.category.edit', $category->id);
                $deleteUrl = route('manage-product.category.destroy', $category->id);
                return '
                    <div class="d-flex justify-content-end gap-2">
                        <a href="'.$editUrl.'" class="btn btn-sm btn-link text-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="'.$deleteUrl.'" method="POST" onsubmit="return confirm(\'Delete this category?\')">
                            '.csrf_field().method_field('DELETE').'
                            <button class="btn btn-sm btn-link text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['name','sort_order','status','actions']) // allow HTML
            ->make(true);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('manage-product.category.edit', compact('category'));
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icon'        => 'nullable|string|max:255',
            'label'       => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = $request->only([
            'name',
            'description',
            'icon',
            'label',
            'sort_order',
            'is_active',
        ]);

        // Update slug if name changed
        if ($category->name !== $request->name) {
            $data['slug'] = Str::slug($request->name);
        }

        // // Upload image
        // if ($request->hasFile('image')) {
        //     $data['image'] = $request->file('image')->store('categories', 'public');
        // }

        if ($request->hasFile('image')) {
        $data['image'] = MyHelper::uploadImage(
            $request->file('image'),
            'categories'
        );
    }

        $category->update($data);

        return redirect()
            ->route('manage-product.category.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Soft delete category
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()
            ->route('manage-product.category.index')
            ->with('success', 'Category deleted successfully');
    }

    /**
     * Toggle status (optional but recommended)
     */
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        return redirect()->back();
    }

  


}