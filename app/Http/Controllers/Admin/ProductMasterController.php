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
        $products = Product::with(['category:id,name','variants.stocks' ])->orderBy('created_at', 'desc')->get(); // Load variants along with their stocks
        return view('manage-product.product-master.index', compact('products'));
    }
    
    
    public function datatable()
    {
        $baseQuery = Product::with(['category', 'variants.stocks'])
        ->orderBy('created_at', 'desc');

        $recordsTotal = (clone $baseQuery)->count();

        $search = request()->input('search.value');
        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $start = (int) request()->input('start', 0);
        $length = (int) request()->input('length', 25);

        $products = $baseQuery->skip($start)->take($length)->get();

        $data = $products->map(function ($p) {
            return [
                'product' => '
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam text-primary"></i>
                    <div>
                            <strong>' . $p->name . '</strong>
                            <div class="small text-muted">#PRD-' . $p->id . '</div>
                        </div>
                    </div>
                ',
                'category' => $p->category->name ?? '-',
                'sku' => $p->sku ?? '-',
                'variants' => '<span class="badge bg-secondary">' . $p->variant_count . ' variants</span>',
                'stock' => '<span class="badge bg-light text-dark">' . $p->total_stock . ' units</span>',
                'status' => '<span class="badge ' . ($p->status === 'active' ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning') . '">'
                    . ucfirst($p->status) . '</span>',
                'actions' => '
                <div class="d-flex justify-content-end gap-2">
                        <a href="' . route('manage-product.product-master.edit', $p->id) . '" class="btn btn-sm btn-link text-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                        <form method="POST" action="' . route('manage-product.product-master.destroy', $p->id) . '">
                            ' . csrf_field() . method_field("DELETE") . '
                        <button class="btn btn-sm btn-link text-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
                ',
            ];
        });

        return response()->json([
            'draw' => (int) request()->input('draw', 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
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
            // ================= VALIDATION =================
            $validated = $request->validate([
                'name'             => 'required|string|max:255',
                'category_id'      => 'required|exists:categories,id',
                'sub_category_id'  => 'nullable|exists:sub_categories,id',
                'brand_id'         => 'nullable|exists:brands,id',
                'sku'              => 'nullable|string|max:100|unique:products,sku',
                'barcode'          => 'nullable|string|max:100|unique:products,barcode',
                'item_code'        => 'nullable|string|max:100|unique:products,item_code',
                'item_type'     => 'nullable|integer',
                'description'      => 'nullable|string',
                'thumbnail_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'collection'  => 'nullable|integer',
                'season'   => 'nullable|integer',
            ]);

            // ================= IMAGE UPLOAD =================
            $thumbnailName = null;

            if ($request->hasFile('thumbnail_image')) {               
                $image_name = 'thumbnail_image-' . date('YmdHis') . uniqid();
                $thumbnailName = MyHelper::uploadImage($request->file('thumbnail_image'),$this->folder, $image_name);
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
                'thumbnail_image' => 'nullable|image',
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