<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get list of categories with their sub-categories
     */
  public function index(Request $request)
{
    $categories = Category::where('is_active', true)
        ->with(['subCategories' => function ($query) {
            $query->where('is_active', true)->orderBy('sort_order');
        }])
        ->orderBy('sort_order')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $categories
    ], 200);
}

public function show($slug, Request $request)
{
    try {
        $perPage = $request->query('per_page', 10);

        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'subCategories' => function ($q) {
                    $q->where('is_active', true)
                      ->orderBy('sort_order');
                },
                'products' => function ($q) {
                    $q->where('status', 'active')
                      ->orderBy('sort_order')
                      ->with([
                          'brand',
                          'images' => function ($q) {
                              $q->where('is_primary', true);
                          },
                          'variants' => function ($q) {
                              $q->where('is_active', true)
                                ->orderBy('sort_order');
                          }
                      ]);
                }
            ])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $category
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Category not found',
            'error' => $e->getMessage()
        ], 404);
    }
}


public function subCategories($categorySlug)
{
    $category = Category::where('slug', $categorySlug)
        ->where('is_active', true)
        ->firstOrFail();

    $subCategories = SubCategory::where('category_id', $category->id)
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    return response()->json([
        'success' => true,
        'data' => [
            'category' => $category,
            'sub_categories' => $subCategories
        ]
    ], 200);
}

public function showSubCategory($slug)
{
    $subCategory = SubCategory::where('slug', $slug)
        ->where('is_active', true)
        ->with('category')
        ->firstOrFail();

    return response()->json([
        'success' => true,
        'data' => $subCategory
    ], 200);
}

public function categorySubCategory($categorySlug, $subCategorySlug)
{
    // ================= CATEGORY =================
    $category = Category::where('slug', $categorySlug)
        ->where('is_active', true)
        ->firstOrFail();

    // ================= SUB CATEGORY =================
    $subCategory = SubCategory::where('slug', $subCategorySlug)
        ->where('category_id', $category->id)
        ->where('is_active', true)
        ->firstOrFail();

    // ================= PRODUCTS =================
    $products = Product::where('category_id', $category->id)
        ->where('sub_category_id', $subCategory->id)
        ->where('status', 'active')
        ->orderBy('sort_order')
        ->with([
            'brand',
            'images' => function ($q) {
                $q->where('is_primary', true);
            },
            'variants' => function ($q) {
                $q->where('is_active', true)
                  ->orderBy('sort_order');
            }
        ])
        ->get();

    return response()->json([
        'success' => true,
        'data' => [
            'category'     => $category,
            'sub_category' => $subCategory,
            'products'     => $products
        ]
    ], 200);
}


}