<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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



class ProductMasterController extends Controller
{
    public $folder;
    function __construct()
    {
        $this->folder = 'products';
    }
    /**
     * Display a listing of products.
     */
   public function index()
{
    $query = Product::with(['category:id,name','variants.stocks'])
        ->orderBy('created_at', 'desc');

    // ✅ If vendor logged in → filter
    if (auth()->user()->user_type === 'vendor') {
        $query->where('vendor_id', auth()->user()->vendor_id);
    }

    $products = $query->get();

    return view('manage-product.product-master.index', compact('products'));
}

    
    
    public function datatable()
{
    // ✅ Base query with eager loading
    $query = Product::with(['category', 'variants.stocks', 'vendor']);

    // ✅ Filter for vendors only
    if (auth()->user()->user_type === 'vendor') {
        $query->where('vendor_id', auth()->user()->vendor_id);
    }

    return DataTables::of($query)
        ->addColumn('product', function ($p) {
            return '<div class="d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam text-primary"></i>
                        <div>
                            <strong>' . $p->name . '</strong>
                            <div class="small text-muted">#PRD-' . $p->id . '</div>
                        </div>
                    </div>';
        })
        ->addColumn('category', function($p) {
            return $p->category->name ?? '-';
        })
        ->addColumn('variants', function($p) {
            $count = $p->variants ? $p->variants->count() : 0;
            return '<span class="badge bg-secondary">' . $count . ' variants</span>';
        })
        ->addColumn('stock', function($p) {
            $stock = 0;
            if ($p->variants) {
                foreach ($p->variants as $v) {
                    $stock += $v->stocks->sum('quantity'); // sum stock for each variant
                }
            }
            return '<span class="badge bg-light text-dark">' . $stock . ' units</span>';
        })
        ->addColumn('status', function($p) {
            $status = $p->approval_status ?? 'pending';
            $class = $status === 'approved' ? 'bg-success' :
                     ($status === 'rejected' ? 'bg-danger' : 'bg-warning');
            $reason = $status === 'rejected' && $p->rejection_reason ? '<div class="small text-danger">'.$p->rejection_reason.'</div>' : '';
            return '<span class="badge '.$class.'">'. ucfirst($status) .'</span>' . $reason;
        })
        ->addColumn('actions', function($p) {

            $approveBtn = '';
            $rejectBtn = '';

            // ✅ Only Admin can approve/reject
            if (auth()->user()->user_type !== 'vendor' && ($p->approval_status ?? 'pending') === 'pending') {
                $approveBtn = '<form method="POST" action="'.route('manage-product.product-master.approve', $p->id).'" class="d-inline approveForm">'.csrf_field().'<button class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></button></form>';
                $rejectBtn  = '<button class="btn btn-sm btn-danger btn-reject" data-url="'.route('manage-product.product-master.reject', $p->id).'"><i class="bi bi-x-circle"></i></button>';
            }

            $editBtn = '<a href="'.route('manage-product.product-master.edit', $p->id).'" class="btn btn-sm btn-link text-primary"><i class="bi bi-pencil"></i></a>';

            $deleteBtn = '';
            // ✅ Vendor can delete only their own products
            if (auth()->user()->user_type !== 'vendor' || ($p->vendor_id === auth()->user()->vendor_id)) {
                $deleteBtn = '<form method="POST" action="'.route('manage-product.product-master.destroy', $p->id).'" class="d-inline">'.csrf_field().method_field('DELETE').'<button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i></button></form>';
            }

            return $approveBtn . ' ' . $rejectBtn . ' ' . $editBtn . ' ' . $deleteBtn;
        })
        ->rawColumns(['product', 'variants', 'stock', 'status', 'actions'])
        ->make(true);
}


 public function approve(Product $product)
    {
        $product->update([
            'approval_status' => 'approved',
            'status'          => 'active',
            'approved_at'     => now(),
            'approved_by'     => auth()->id(),
            'rejection_reason'=> null,
        ]);

        return back()->with('success', 'Product approved successfully');
    }

    /**
     * Reject product with reason.
     */
    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $product->update([
            'approval_status'  => 'rejected',
            'status'           => 'inactive',
            'rejection_reason' => $request->rejection_reason,
            'approved_by'      => auth()->id(),
        ]);

        return back()->with('success', 'Product rejected successfully');
    }



    public function discounts($productId)
    {
        $product = Product::findOrFail($productId);
        $today = now()->toDateString();

        $coupons = Coupon::query()
            ->where('coupon_type', 'discount')
            ->where('is_active', true)
            ->whereDate('valid_from', '<=', $today)
            ->whereDate('valid_to', '>=', $today)
            ->where(function ($query) use ($product) {
                $query->where('applicable_to', 'all');

                if (!empty($product->category_id)) {
                    $query->orWhere(function ($q) use ($product) {
                        $q->where('applicable_to', 'category')
                            ->where('category_id', $product->category_id);
                    });
                }

                if (!empty($product->sub_category_id)) {
                    $query->orWhere(function ($q) use ($product) {
                        $q->where('applicable_to', 'sub_category')
                            ->where('sub_category_id', $product->sub_category_id);
                    });
                }

                if (!empty($product->id)) {
                    $query->orWhere(function ($q) use ($product) {
                        $q->where('applicable_to', 'product')
                            ->where('product_id', $product->id);
                    });
                }

                if (!empty($product->brand_id)) {
                    $query->orWhere(function ($q) use ($product) {
                        $q->where('applicable_to', 'brand')
                            ->where('brand_id', $product->brand_id);
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $coupons,
        ]);
    }



    public function getSubCategories($categoryId)
    {
        return SubCategory::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name']);
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
        return view('manage-product.product-master.create', compact('collections','itemTypes','seasons','categories','brands','stores','variants'));
    }


   public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // ================= AUTH USER =================
        $user = auth()->user();

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

            // Admin only (optional)
            'vendor_id'        => 'nullable|exists:users,vendor_id',
        ]);

        // ================= IMAGE UPLOAD =================
        $thumbnailName = null;

        if ($request->hasFile('thumbnail_image')) {
            $image_name = 'thumbnail_image-' . now()->format('YmdHis') . uniqid();
            $thumbnailName = MyHelper::uploadImage(
                $request->file('thumbnail_image'),
                $this->folder,
                $image_name
            );
        }

        // ================= VENDOR ID LOGIC =================
        $vendorId = null;

        if ($user->user_type === 'vendor') {
            // Vendor can only create product for himself
            $vendorId = $user->vendor_id;
        } elseif ($user->user_type === 'admin') {
            // Admin may assign vendor manually (optional)
            $vendorId = $request->vendor_id;
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

            // vendor ownership
            'vendor_id'       => $vendorId,

            // switches
            'status'          => 1,
            'is_featured'     => 1,
        ]);

        DB::commit();

        return redirect()
            ->route('manage-product.product-master.index')
            ->with('success', 'Product created successfully');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function edit($id)
    {
        $user = auth()->user();
        $product = Product::with(['variants.stocks','variants.prices','category','subCategory','brand'])->findOrFail($id);
        $brands = Brand::where('is_active', true)->orderBy('sort_order')->get();
        $masters = OptionMaster::whereIn('name', ['Collections','Item type','Seasons'])->get()->keyBy('name');

        $collections = isset($masters['Collections'])
            ? Option::where('option_master_id', $masters['Collections']->id)->where('status', 1)->get()
            : collect();

        $itemTypes = isset($masters['Item type'])
            ? Option::where('option_master_id', $masters['Item type']->id)->where('status', 1)->get()
            : collect();

        $seasons = isset($masters['Seasons'])
            ? Option::where('option_master_id', $masters['Seasons']->id)->where('status', 1)->get()
            : collect();

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($user->hasRole('Super Admin')) {
        $allowedStoreIds = Store::where('status', 1)->pluck('id');
        $stores = Store::where('status', 1)->orderBy('name')->get();
        $defaultStoreId = $stores->first()?->id ?? null; // Auto select first store
    } else {
        $allowedStoreIds = collect();
        if ($user->store_id) $allowedStoreIds->push($user->store_id);
        $allowedStoreIds = $allowedStoreIds->merge($user->stores()->pluck('stores.id'))->unique();

        $stores = Store::whereIn('id', $allowedStoreIds)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $defaultStoreId = $user->store_id ?? $allowedStoreIds->first() ?? null;
    }

        return view('manage-product.product-master.edit', compact(
            'product',
            'collections',
            'itemTypes',
            'seasons',
            'categories',
            'subCategories',
            'brands',
            'stores',
        'defaultStoreId',
        
        ));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'category_id'     => 'required|exists:categories,id',
                'sub_category_id' => 'nullable|exists:sub_categories,id',
                'brand_id'        => 'nullable|exists:brands,id',
                'sku'             => 'nullable|unique:products,sku,' . $product->id,
                'barcode'         => 'nullable|string',
                'description'     => 'nullable|string',
                'weight'          => 'nullable|numeric',
                'weight_unit'     => 'nullable|string',
                'item_type'    => 'nullable|integer',
                'collection' => 'nullable|integer',
                'season'  => 'nullable|integer',
                'length'          => 'nullable|numeric',
                'width'           => 'nullable|numeric',
                'height'          => 'nullable|numeric',
                'min_order_quantity' => 'nullable|integer|min:1',
                'status'          => 'nullable|in:active,inactive',
                'thumbnail_image' => 'nullable|image|max:2048',
            ]);
            if ($request->hasFile('thumbnail_image')) {
                if ($product->thumbnail_image) {
                    MyHelper::removeImage($product->thumbnail_image, $this->folder);
                }
                $image_name = 'thumbnail_image-' . date('YmdHis') . '-' . $product->id;
                $validated['thumbnail_image'] = MyHelper::uploadImage($request->file('thumbnail_image'),$this->folder, $image_name);
            }
            $validated['slug'] = Str::slug($validated['name']);
            $product->update($validated);
            DB::commit();
            return redirect()
                ->route('manage-product.product-master.edit', $id)
                ->with('success', 'Product updated successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function updateInfo(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $validated = $request->validate([
                'description'     => 'nullable|string',
                'season'  => 'nullable|integer',
                'length'          => 'nullable|numeric',
                'width'           => 'nullable|numeric',
                'height'          => 'nullable|numeric',
                'min_order_quantity' => 'nullable|integer|min:1',
            ]);
            
            $product->update($validated);
            DB::commit();
            return redirect()
                ->route('manage-product.product-master.edit', $id)
                ->with('success', 'Product updated successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateVariant(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $variants = $request->input('variants', []);
            foreach ($variants as $variantData) {
                ProductVariant::updateOrCreate(
                    ['id' => $variantData['id'] ?? null],
                    [
                        'product_id' => $product->id,
                        'name' => $variantData['name'],
                        'sku' => $variantData['sku'] ?? null,
                        'variant_type' => $variantData['variant_type'] ?? null,
                        'variant_value' => $variantData['variant_value'] ?? null,
                        'barcode' => $variantData['barcode'] ?? null,
                        'is_active' => $variantData['is_active'] ?? 1,
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Product variants saved successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);
            $product->variants()->delete();
            $product->delete();
            DB::commit();
            return redirect()
                ->route('manage-product.product-master.index')
                ->with('success', 'Product deleted successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }



}