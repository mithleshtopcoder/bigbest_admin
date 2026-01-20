<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        // ✅ Vendor products only
        if ($request->ajax()) {
            $products = Product::with(['category', 'variants.stocks'])
                ->where('vendor_id', auth()->id())
                ->orderBy('created_at', 'desc');

            return DataTables::of($products)
                ->addColumn('product', function ($p) {
                    return '<div class="d-flex align-items-center gap-2">
                                <i class="bi bi-box-seam text-primary"></i>
                                <div>
                                    <strong>' . $p->name . '</strong>
                                    <div class="small text-muted">#PRD-' . $p->id . '</div>
                                </div>
                            </div>';
                })
                ->addColumn('category', fn($p) => $p->category->name ?? '-')
                ->addColumn('variants', function($p) {
                    $count = $p->variants ? $p->variants->count() : 0;
                    return '<span class="badge bg-secondary">' . $count . ' variants</span>';
                })
                ->addColumn('stock', function($p) {
                    $stock = $p->variants->sum(fn($v) => $v->stocks->sum('quantity'));
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
                    $editBtn = '<a href="'.route('vendor.products.edit', $p->id).'" class="btn btn-sm btn-link text-primary"><i class="bi bi-pencil"></i></a>';
                    $deleteBtn = '<form method="POST" action="'.route('vendor.products.destroy', $p->id).'" class="d-inline">'
                                  . csrf_field() . method_field('DELETE') .
                                  '<button class="btn btn-sm btn-link text-danger"><i class="bi bi-trash"></i></button></form>';
                    return $editBtn . ' ' . $deleteBtn;
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
    dd(auth()->user());
    DB::beginTransaction();

    try {
        $user = auth()->user(); // logged-in vendor
        $vendorId = $user->vendor_id; // ✅ vendor_id is already in user table

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
            $image_name = 'thumbnail_image-' . date('YmdHis') . uniqid();
            $thumbnailName = MyHelper::uploadImage($request->file('thumbnail_image'), $this->folder, $image_name);
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
    'status'          => 0, // pending by default
    'approval_status' => 'pending',
    'vendor_id'       => auth()->user()->vendor_id, // ✅ vendor table ID
]);


        DB::commit();

        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Product submitted successfully and is pending admin approval.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function edit(Product $product)
    {
        // ✅ Ensure vendor can edit only their own products
        if ($product->vendor_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $brands = Brand::where('is_active', true)->orderBy('sort_order')->get();

        return view('vendor.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string',
            'thumbnail_image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('thumbnail_image')) {
                if ($product->thumbnail_image) {
                    MyHelper::removeImage($product->thumbnail_image, $this->folder);
                }
                $thumbnailName = 'thumbnail_image-' . date('YmdHis') . '-' . $product->id;
                $product->thumbnail_image = MyHelper::uploadImage($request->file('thumbnail_image'), $this->folder, $thumbnailName);
            }

            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'brand_id' => $request->brand_id,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
            ]);

            DB::commit();
            return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
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