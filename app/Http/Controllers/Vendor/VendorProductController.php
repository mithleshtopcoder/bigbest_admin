<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\OptionMaster;
use App\Models\Option;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use App\Models\ProductImage;
use App\Models\Coupon;
use App\Helpers\MyHelper;
use Yajra\DataTables\Facades\DataTables;
use DB;

class VendorProductController extends Controller
{
    protected $folder = 'products';

    public function index(Request $request)
{
    if ($request->ajax()) {

        $products = Product::with(['category', 'variants.stocks'])
            ->where('vendor_id', auth()->user()->vendor_id) // ✅ FIX
            ->orderBy('created_at', 'desc');

        return DataTables::of($products)
            ->addColumn('product', function ($p) {
                return '<div class="d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam text-primary"></i>
                            <div>
                                <strong>' . e($p->name) . '</strong>
                                <div class="small text-muted">#PRD-' . $p->id . '</div>
                            </div>
                        </div>';
            })
            ->addColumn('category', fn($p) => $p->category->name ?? '-')
            ->addColumn('variants', function ($p) {
                return '<span class="badge bg-secondary">' . $p->variants->count() . ' variants</span>';
            })
            ->addColumn('stock', function ($p) {
                $stock = $p->variants->sum(fn ($v) => $v->stocks->sum('quantity'));
                return '<span class="badge bg-light text-dark">' . $stock . ' units</span>';
            })
            ->addColumn('status', function ($p) {
                $status = $p->approval_status ?? 'pending';
                $class = match ($status) {
                    'approved' => 'bg-success',
                    'rejected' => 'bg-danger',
                    default => 'bg-warning'
                };

                $reason = $status === 'rejected' && $p->rejection_reason
                    ? '<div class="small text-danger">' . e($p->rejection_reason) . '</div>'
                    : '';

                return '<span class="badge ' . $class . '">' . ucfirst($status) . '</span>' . $reason;
            })
            ->addColumn('actions', function ($p) {
                return '
                    <a href="' . route('vendor.products.edit', $p->id) . '" class="btn btn-sm btn-link text-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="' . route('vendor.products.destroy', $p->id) . '" class="d-inline">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-link text-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['product', 'variants', 'stock', 'status', 'actions'])
            ->make(true);
    }

    return view('admin.vendors.products.index');
}


    public function create()
    {
        $brands = Brand::where('is_active', true)->orderBy('sort_order')->get();
        $masters = OptionMaster::whereIn('name', ['Collections','Item type','Seasons'])->get()->keyBy('name');
        $collections = collect();
        $itemTypes   = collect();
        $seasons     = collect();
        if (isset($masters['Collections'])) {
            $collections = Option::where('option_master_id', $masters['Collections']->id)->where('status', 1)->get();
        }
        // ✅ FIXED KEY NAME
        if (isset($masters['Item type'])) {
            $itemTypes = Option::where('option_master_id', $masters['Item type']->id)->where('status', 1)->get();
        }
        if (isset($masters['Seasons'])) {
            $seasons = Option::where('option_master_id', $masters['Seasons']->id)->where('status', 1)->get();
        }
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $stores = Store::where('status', 1)->orderBy('name')->get();
        // ✅ VARIANTS (EMPTY ON CREATE PAGE)
        $variants = collect();
        return view('admin.vendors.products.create', compact('collections','itemTypes','seasons','categories','brands','stores','variants'));
    }

public function store(Request $request)
{
    //  dd(request(all));
    DB::beginTransaction();

    try {
        $user = auth()->user(); // logged-in vendor (user)

        // ================= VALIDATION =================
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'sub_category_id'  => 'nullable|exists:sub_categories,id',
            'brand_id'         => 'nullable|exists:brands,id',
            'sku'              => 'nullable|string|max:100|unique:products,sku',
            'barcode'          => 'nullable|string|max:100|unique:products,barcode',
            'item_code'        => 'nullable|string|max:100|unique:products,item_code',
            'item_type'        => 'nullable|integer',
            'description'      => 'nullable|string',
            'thumbnail_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'collection'       => 'nullable|integer',
            'season'           => 'nullable|integer',
        ]);

        // ================= IMAGE UPLOAD =================
        $thumbnailName = null;
        if ($request->hasFile('thumbnail_image')) {
            $imageName = 'thumbnail_image-' . date('YmdHis') . uniqid();
            $thumbnailName = MyHelper::uploadImage(
                $request->file('thumbnail_image'),
                $this->folder,
                $imageName
            );
        }

        // ================= CREATE PRODUCT =================
        $product = Product::create([
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'sku'             => $request->sku,
            'description'     => $request->description,
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'brand_id'        => $request->brand_id,
            'barcode'         => $request->barcode,
            'item_type'       => $request->item_type,
            'item_code'       => $request->item_code,
            'collection'      => $request->collection,
            'season'          => $request->season,
            'thumbnail_image' => $thumbnailName,
            'status'          => 'inactive', // pending
            'approval_status' => 'pending',
            'vendor_id' => $user->vendor_id, 
        ]);

        DB::commit();

        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Product submitted successfully and is pending admin approval.');

    } catch (\Exception $e) {
        DB::rollBack();
 dd([
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'trace'   => $e->getTraceAsString(),
    ]);
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}


   public function edit($id)
{
    $user = auth()->user();

    // ✅ Fetch product
    $product = Product::with([
        'variants.stocks',
        'variants.prices',
        'category',
        'subCategory',
        'brand'
    ])->findOrFail($id);

    // ✅ Vendor authorization check (IMPORTANT)
    if (!$user->hasRole('Super Admin') && $product->vendor_id !== $user->vendor_id) {
        abort(403, 'Unauthorized access');
    }

    // ================= MASTER DATA =================
    $brands = Brand::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $masters = OptionMaster::whereIn('name', ['Collections', 'Item type', 'Seasons'])
        ->get()
        ->keyBy('name');

    $collections = isset($masters['Collections'])
        ? Option::where('option_master_id', $masters['Collections']->id)
            ->where('status', 1)
            ->get()
        : collect();

    $itemTypes = isset($masters['Item type'])
        ? Option::where('option_master_id', $masters['Item type']->id)
            ->where('status', 1)
            ->get()
        : collect();

    $seasons = isset($masters['Seasons'])
        ? Option::where('option_master_id', $masters['Seasons']->id)
            ->where('status', 1)
            ->get()
        : collect();

    $categories = Category::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $subCategories = SubCategory::where('category_id', $product->category_id)
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    // ================= STORE LOGIC =================
    if ($user->hasRole('Super Admin')) {

        $stores = Store::where('status', 1)
            ->orderBy('name')
            ->get();

        $defaultStoreId = $stores->first()?->id;

    } else {

        $allowedStoreIds = collect();

        if ($user->store_id) {
            $allowedStoreIds->push($user->store_id);
        }

        $allowedStoreIds = $allowedStoreIds
            ->merge($user->stores()->pluck('stores.id'))
            ->unique();

        $stores = Store::whereIn('id', $allowedStoreIds)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $defaultStoreId = $user->store_id ?? $allowedStoreIds->first();
    }

    // ================= VIEW =================
    return view('admin.vendors.products.edit', compact(
        'product',
        'collections',
        'itemTypes',
        'seasons',
        'categories',
        'subCategories',
        'brands',
        'stores',
        'defaultStoreId'
    ));
}



    public function update(Request $request, Product $product)
{
    // ✅ Vendor security
    if ($product->vendor_id !== auth()->user()->vendor_id) {
        abort(403);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'nullable|exists:sub_categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'sku' => 'nullable|unique:products,sku,' . $product->id,
        'barcode' => 'nullable|unique:products,barcode,' . $product->id,
        'item_code' => 'nullable|unique:products,item_code,' . $product->id,
        'description' => 'nullable|string',
        'thumbnail_image' => 'nullable|image|max:2048',
        'collection' => 'nullable|integer',
        'season' => 'nullable|integer',
        'item_type' => 'nullable|integer',
    ]);

    if ($request->hasFile('thumbnail_image')) {
        $validated['thumbnail_image'] =
            MyHelper::uploadImage($request->file('thumbnail_image'), 'products');
    }

    $product->update($validated);

    return redirect()
        ->route('vendor.products.index')
        ->with('success', 'Product updated successfully');
}

public function updateInfo(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $product = Product::findOrFail($id); // product-id wise only

        $validated = $request->validate([
            'unit'                => 'required|string',
            'weight_unit'         => 'required|string',
            'status'              => 'required|in:active,inactive',

            'min_order_quantity'  => 'nullable|integer|min:1',
            'max_order_quantity'  => 'nullable|integer|min:1',

            'weight'              => 'nullable|numeric|min:0',
            'length'              => 'nullable|numeric|min:0',
            'width'               => 'nullable|numeric|min:0',
            'height'              => 'nullable|numeric|min:0',
        ]);

        $product->update($validated);

        DB::commit();

        return redirect()
            ->route('admin.vendors.products.edit', $id)
            ->with('success', 'Product updated successfully');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth()->id()) abort(403);

        DB::beginTransaction();
        try {
            $product->delete();
            DB::commit();
            return redirect()->route('vendor.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}