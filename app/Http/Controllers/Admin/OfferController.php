<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    /**
     * Display a listing of offers by type.
     */
  public function index($type)
{
    $coupons = Coupon::with([
            'category:id,name',
            'subCategory:id,name',
            'product:id,name',
            'brand:id,name'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('manage-product.offer.index', compact('type', 'coupons'));
}
    /**
     * Show the form for creating a new offer.
     */
    public function create($type)
{
    $categories = Category::where('is_active', true)
        ->orderBy('name')
        ->get();

    $subCategories = SubCategory::where('is_active', true)
        ->orderBy('name')
        ->get();

    $products = Product::where('status', 1)
        ->orderBy('name')
        ->get();

    $brands = Brand::where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('manage-product.offer.create', compact(
        'type',
        'categories',
        'subCategories',
        'products',
        'brands'
    ));
}

public function getSubCategories($categoryId)
{
    return response()->json(
        SubCategory::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name'])
    );
}

public function getProducts($categoryId, $subCategoryId = null)
{
    $query = Product::where('category_id', $categoryId)
        ->whereIn('status', [1, 'active']); // supports both

    if (!empty($subCategoryId)) {
        $query->where('sub_category_id', $subCategoryId);
    }

    return response()->json(
        $query->orderBy('name')->get(['id', 'name'])
    );
}

public function store(Request $request, $type)
{
    $couponType = rtrim((string) $type, 's');
    if (!in_array($couponType, ['coupon', 'discount'], true)) {
        $couponType = 'coupon';
    }

    // 1️⃣ VALIDATION
    $validated = $request->validate([
        'code' => 'required|string|max:50|unique:coupons,code',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',

        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',

        'minimum_order_amount' => 'nullable|numeric|min:0',
        'maximum_discount_amount' => 'nullable|numeric|min:0',

        'applicable_to' => 'nullable|in:all,category,sub_category,product,brand',

        'category_id' => 'nullable|exists:categories,id',
        'sub_category_id' => 'nullable|exists:sub_categories,id',
        'product_id' => 'nullable|exists:products,id',
        'brand_id' => 'nullable|exists:brands,id',

        'valid_from' => 'required|date',
        'valid_to' => 'required|date|after_or_equal:valid_from',

        'usage_limit' => 'nullable|integer|min:1',
        'usage_limit_per_user' => 'nullable|integer|min:1',

        'terms_conditions' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {

        // 2️⃣ CLEAR UNUSED FIELDS BASED ON applicable_to
        $validated['applicable_to'] = $validated['applicable_to'] ?? 'all';

        switch ($validated['applicable_to']) {
            case 'category':
                $validated['sub_category_id'] = null;
                $validated['product_id'] = null;
                $validated['brand_id'] = null;
                break;

            case 'sub_category':
                $validated['product_id'] = null;
                $validated['brand_id'] = null;
                break;

            case 'product':
                $validated['brand_id'] = null;
                break;

            case 'brand':
                $validated['category_id'] = null;
                $validated['sub_category_id'] = null;
                $validated['product_id'] = null;
                break;

            default: // Applies to ALL
                $validated['category_id'] = null;
                $validated['sub_category_id'] = null;
                $validated['product_id'] = null;
                $validated['brand_id'] = null;
        }

        // 3️⃣ CREATE COUPON
        Coupon::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,

            'coupon_type' => $couponType,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],

            'minimum_order_amount' => $validated['minimum_order_amount'] ?? 0,
            'maximum_discount_amount' => $validated['maximum_discount_amount'] ?? null,

            'applicable_to' => $validated['applicable_to'] ?? null,

            'category_id' => $validated['category_id'] ?? null,
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'product_id' => $validated['product_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,

            'valid_from' => $validated['valid_from'],
            'valid_to' => $validated['valid_to'],

            'usage_limit' => $validated['usage_limit'] ?? null,
            'usage_limit_per_user' => $validated['usage_limit_per_user'] ?? null,
            'used_count' => 0,

            'is_active' => $request->has('is_active'),
            'is_first_order_only' => $request->has('is_first_order_only'),

            'terms_conditions' => $validated['terms_conditions'] ?? null,
        ]);

        DB::commit();

        return redirect()
            ->route('manage-product.offer.index', $type)
            ->with('success', 'Coupon created successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


public function datatable(Request $request, $type)
{
    $couponType = rtrim((string) $type, 's');
    if (!in_array($couponType, ['coupon', 'discount'], true)) {
        $couponType = 'coupon';
    }

    $query = Coupon::with(['category', 'subCategory', 'product', 'brand'])->where('coupon_type', $couponType)
        ->orderBy('created_at', 'desc');

    $recordsTotal = $query->count();

    // Basic search (code/name)
    $search = $request->input('search.value');
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('code', 'like', '%' . $search . '%')
              ->orWhere('name', 'like', '%' . $search . '%');
        });
    }

    $recordsFiltered = $query->count();

    $start = (int) $request->input('start', 0);
    $length = (int) $request->input('length', 25);

    $coupons = $query->skip($start)->take($length)->get();

    $data = $coupons->map(function ($c) {
        return [
            'id' => $c->id,
            'code' => $c->code,
            'name' => $c->name,
            'discount' => $c->discount_type === 'percentage'
                ? $c->discount_value . '%'
                : '₹' . number_format($c->discount_value, 2),
            'applies_to' => ucfirst(str_replace('_', ' ', $c->applicable_to ?? 'All')),
            'target' => $c->category->name
                ?? $c->subCategory->name
                ?? $c->product->name
                ?? $c->brand->name
                ?? '-',
            'status' => $c->is_active
            ? '<span class="badge bg-soft-success text-success">Active</span>'
                : '<span class="badge bg-soft-danger text-danger">Inactive</span>',
            'validity' => $c->valid_from->format('d M Y') . ' - ' . $c->valid_to->format('d M Y'),
        ];
    });

    return response()->json([
        'draw' => (int) $request->input('draw', 1),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data,
    ]);
}

   public function edit($type, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $categories = Category::all();
        $subCategories = $coupon->category ? SubCategory::where('category_id', $coupon->category_id)->get() : collect();
        $products = $coupon->subCategory ? Product::where('category_id', $coupon->category_id)
                                        ->where('sub_category_id', $coupon->sub_category_id)
                                        ->get() : collect();
        $brands = Brand::all();

        return view('manage-product.offer.edit', compact(
            'type', 'coupon', 'categories', 'subCategories', 'products', 'brands'
        ));
    }

    /**
     * Update the offer
     */
    public function update(Request $request, $type, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $couponType = rtrim((string) $type, 's');
        if (!in_array($couponType, ['coupon', 'discount'], true)) {
            $couponType = 'coupon';
        }

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'applicable_to' => 'nullable|in:all,category,sub_category,product,brand',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'product_id' => 'nullable|exists:products,id',
            'brand_id' => 'nullable|exists:brands,id',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'is_first_order_only' => 'nullable|boolean',
            'terms_conditions' => 'nullable|string',
        ]);

        // Normalize applicable_to and clear unused fields
        $validated['applicable_to'] = $validated['applicable_to'] ?? 'all';
        switch ($validated['applicable_to']) {
            case 'category':
                $validated['sub_category_id'] = null;
                $validated['product_id'] = null;
                $validated['brand_id'] = null;
                break;
            case 'sub_category':
                $validated['product_id'] = null;
                $validated['brand_id'] = null;
                break;
            case 'product':
                $validated['brand_id'] = null;
                break;
            case 'brand':
                $validated['category_id'] = null;
                $validated['sub_category_id'] = null;
                $validated['product_id'] = null;
                break;
            default:
                $validated['category_id'] = null;
                $validated['sub_category_id'] = null;
                $validated['product_id'] = null;
                $validated['brand_id'] = null;
        }

        // Cast checkboxes
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['is_first_order_only'] = $request->has('is_first_order_only') ? 1 : 0;
        $validated['coupon_type'] = $couponType;

        $coupon->update($validated);

        return redirect()->route('manage-product.offer.index', $type)
                         ->with('success', 'Offer updated successfully.');
    }
    // public function coupons()
    // {
    //     return view('admin.offers.coupons');
    // }

    public function discounts()
    {
        return view('admin.offers.discounts');
    }

    public function comboOffers()
    {
        return view('admin.offers.combo-offers');
    }

    public function posOffers()
    {
        return view('admin.offers.pos-offers');
    }

    public function appOffers()
    {
        return view('admin.offers.app-offers');
    }
}