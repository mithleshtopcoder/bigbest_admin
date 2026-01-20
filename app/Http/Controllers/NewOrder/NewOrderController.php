<?php

namespace App\Http\Controllers\NewOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\AppSetting;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class NewOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type)
    {
        if ($type === 'pos') {
            $orders = Order::with(['customer', 'store'])->where('order_source', 'pos')->orderBy('id', 'desc')->paginate(10); // Only POS orders
            
            return view('new-orders.pos.index', compact('orders'));
        }
        
        if ($type === 'online') {
            // ✅ Fetch ONLY online orders
            $orders = Order::with('customer')
            ->where('order_source', 'online') // Important filter
            ->latest()
            ->paginate(10);
            
            return view('new-orders.online.index', compact('orders'));
        }
        
        // Optional: handle unknown type
        abort(404, 'Order type not found');
    }
    
    
    
  public function datatable(Request $request)
{
    $user = auth()->user();

    $orders = Order::with(['store', 'customer'])
        ->where('order_source', 'pos');

    // 🔐 Restrict by store for non-Super Admin
    if (!$user->roles()->where('name', 'Super Admin')->exists()) {

        $storeIds = collect();

        if ($user->store_id) {
            $storeIds->push($user->store_id);
        }

        $pivotStoreIds = $user->stores()->pluck('stores.id');
        $storeIds = $storeIds->merge($pivotStoreIds)->unique();

        $orders->whereIn('store_id', $storeIds);
    }

    $orders->orderBy('created_at', 'desc');

    return datatables()->of($orders)
        ->addColumn('date', fn($o) => $o->created_at->format('Y-m-d'))
        ->addColumn('time', fn($o) => $o->created_at->format('h:i A'))
        ->addColumn('store', fn($o) => $o->store->name ?? 'N/A')
        ->addColumn('customer', fn($o) => $o->customer
            ? $o->customer->first_name.' '.($o->customer->last_name ?? '')
            : 'Walk-in')
        ->addColumn('items', fn($o) => $o->total_items ?? 0)
        ->addColumn('amount', fn($o) => number_format($o->total_amount, 2))
        ->addColumn('payment', fn($o) => $o->payment_method
            ? ucfirst(str_replace('_', ' ', $o->payment_method))
            : 'N/A')
        ->addColumn('status', function ($o) {
            $status = strtolower($o->status);
            $class = match ($status) {
                'hold' => 'bg-warning text-dark',
                'pending' => 'bg-info text-white',
                'delivered' => 'bg-success text-white',
                'cancelled' => 'bg-danger text-white',
                default => 'bg-secondary text-white'
            };

            return '<span class="badge '.$class.'">'.ucfirst(str_replace('_',' ',$status)).'</span>';
        })
        ->addColumn('status_raw', fn($o) => strtolower($o->status))
        ->addColumn('action', function ($o) {
            $buttons = '';

            if ($o->status === 'hold') {
                $buttons .= '<a href="'.route('new-order.resume', $o->id).'" class="btn btn-sm btn-primary me-1">Resume</a>';
            }

            $buttons .= '<a href="'.route('new-order.view', ['type'=>'pos','id'=>$o->id]).'" class="btn btn-sm btn-secondary">View</a>';

            return '<div class="d-flex gap-1">'.$buttons.'</div>';
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
}





public function resume(Order $order)
{
    // Only allow hold orders to be resumed
    if ($order->status !== 'hold') {
        return redirect()->route('new-order.index', 'pos')->with('error', 'Only hold orders can be resumed.');
    }

    // Load all necessary relations for products, customer, etc.
    $order->load(['customer', 'items.product', 'items.productVariant']);
    
    // Get stores - if Super Admin, show all stores; otherwise, show only assigned stores
    $storesQuery = Store::where('status', 1);
    
    // If user is not Super Admin, filter by assigned stores
    if (!auth()->user()->roles()->where('name', 'Super Admin')->where('is_active', true)->exists()) {
        $storesQuery->whereHas('users', function($query) {
            $query->where('users.id', auth()->id());
        });
    }
    
    $stores = $storesQuery->orderBy('name')->get();

    // Reuse the POS create view, but pass the old order as `$order`
    return view('new-orders.pos.create', compact('order','stores'));
}



public function datatableonline()
{
    $user = auth()->user();

    $orders = Order::with('customer')
        ->where('order_source', 'online');

    // 🔐 Restrict data for non Super Admin
    if (! $user->hasRole('Super Admin')) {
        $orders->where('store_id', $user->store_id);
    }

    return datatables()->of($orders)
        ->addColumn('order', function ($o) {
            return '<div class="d-flex align-items-center gap-2">
                        <i class="bi bi-cart-check text-primary fs-5"></i>
                        <a>#'.$o->order_number.'</a>
                    </div>';
        })
        ->addColumn('date_time', function ($o) {
            return $o->created_at->format('Y-m-d') .
                '<br><small class="text-muted">' .
                $o->created_at->format('h:i A') .
                '</small>';
        })
        ->addColumn('customer', function ($o) {
            $name  = $o->customer->full_name ?? 'Guest';
            $email = $o->customer->email ?? '';
            return '<a href="javascript:void(0)">
                        '.$name.'<br>
                        <small class="text-muted">'.$email.'</small>
                    </a>';
        })
        ->addColumn('items', fn($o) =>
            '<span class="badge bg-gray-200 text-dark">'.($o->items_count ?? 0).' items</span>'
        )
        ->addColumn('amount', fn($o) =>
            '<strong>₹'.number_format($o->total_amount, 2).'</strong>'
        )
        ->addColumn('payment', function ($o) {
            $cls = strtolower($o->payment_status) === 'paid' ? 'success' : 'warning';
            return '<span class="badge bg-soft-'.$cls.' text-'.$cls.'">'.
                ucfirst($o->payment_status).
            '</span>';
        })
        ->addColumn('status', function ($o) {
            $statusClass = match($o->status) {
                'accepted', 'confirmed' => 'bg-soft-primary text-primary',
                'preparing', 'processing' => 'bg-soft-info text-info',
                'out-for-delivery', 'out_for_delivery' => 'bg-soft-warning text-warning',
                'completed', 'delivered' => 'bg-soft-success text-success',
                'cancelled' => 'bg-soft-danger text-danger',
                default => 'bg-soft-secondary text-secondary'
            };

            return '<span class="badge '.$statusClass.'">'.
                ucfirst(str_replace('_',' ',$o->status)).
            '</span>';
        })
        ->addColumn('action', function ($o) {
            $url = route('new-order.view', ['type' => 'online', 'id' => $o->id]);
            return '<div class="d-flex justify-content-end gap-1">
                        <a href="'.$url.'" class="btn btn-sm btn-link text-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>';
        })
        ->rawColumns([
            'order','date_time','customer',
            'items','amount','payment','status','action'
        ])
        ->make(true);
}



    /**
     * Show the form for creating a new resource.
     */
 public function create()
{
    $user = auth()->user();

    $isSuperAdmin = $user->hasRole('Super Admin');

    if ($isSuperAdmin) {
        $stores = Store::where('status', 1)
            ->orderBy('name')
            ->get();
    } else {
        $userStoreIds = collect();

        if ($user->store_id) {
            $userStoreIds->push($user->store_id);
        }

        $pivotStoreIds = $user->stores()->pluck('stores.id');

        $storeIds = $userStoreIds
            ->merge($pivotStoreIds)
            ->unique()
            ->values();

        $stores = Store::whereIn('id', $storeIds)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
    }

    // ✅ AUTO SELECT PRIORITY
    $autoStoreId = null;

    if ($user->store_id && $stores->contains('id', $user->store_id)) {
        // 1️⃣ First priority: users.store_id
        $autoStoreId = $user->store_id;
    } elseif ($stores->count() === 1) {
        // 2️⃣ Fallback: only one store available
        $autoStoreId = $stores->first()->id;
    }

    return view('new-orders.pos.create', compact('stores', 'autoStoreId'));
}





    /**
     * Search customers by phone number
     */
    public function searchCustomers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        // Search by phone first (exact matches prioritized), then by name
        $customers = Customer::where(function($q) use ($query) {
                $q->where('phone', 'like', '%' . $query . '%')
                  ->orWhere('first_name', 'like', '%' . $query . '%')
                  ->orWhere('last_name', 'like', '%' . $query . '%');
            })
            ->select('id', 'first_name', 'last_name', 'phone', 'email')
            ->orderByRaw("CASE WHEN phone LIKE ? THEN 0 ELSE 1 END", [$query . '%'])
            ->orderBy('first_name')
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => trim($customer->first_name . ' ' . ($customer->last_name ?? '')),
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                ];
            });

        return response()->json(['data' => $customers]);
    }

    /**
     * Search products by name or barcode
     */
 public function searchProducts(Request $request)
{
    $query   = $request->get('q', '');
    $storeId = $request->get('store_id');

    // ⛔ Minimum validation
    if (strlen($query) < 2 || !$storeId) {
        return response()->json(['data' => []]);
    }

    $products = Product::where('status', 'active')
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('barcode', 'like', "%{$query}%")
              ->orWhere('sku', 'like', "%{$query}%");
        })

        // ✅ Product must have at least one variant in this store with stock
        ->whereHas('variants', function ($variantQuery) use ($storeId) {
            $variantQuery->where('is_active', true)
                ->whereHas('stocks', function ($stockQuery) use ($storeId) {
                    $stockQuery->where('store_id', $storeId)
                               ->where('quantity', '>', 0);
                });
        })

        ->with([
            // ✅ Load ONLY variants available in selected store
            'variants' => function ($variantQuery) use ($storeId) {
                $variantQuery->where('is_active', true)
                    ->whereHas('stocks', function ($stockQuery) use ($storeId) {
                        $stockQuery->where('store_id', $storeId)
                                   ->where('quantity', '>', 0);
                    })
                    ->with([
                        // 🔥 VERY IMPORTANT → restrict stock to selected store
                        'stocks' => function ($stockQuery) use ($storeId) {
                            $stockQuery->where('store_id', $storeId);
                        }
                    ])
                    ->orderBy('is_default', 'desc')
                    ->orderBy('sort_order');
            },

            // ✅ Load valid active price
            'variants.prices' => function ($priceQuery) {
                $priceQuery->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('effective_from')
                          ->orWhere('effective_from', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('effective_to')
                          ->orWhere('effective_to', '>=', now());
                    })
                    ->orderBy('effective_from', 'desc');
            }
        ])

        ->limit(10)
        ->get()

        // 🔄 Convert to POS-friendly flat structure
        ->flatMap(function ($product) {
            return $product->variants->map(function ($variant) use ($product) {

                $price = $variant->prices->first();
                $stock = $variant->stocks->first(); // ✔ Correct store stock

                return [
                    'product_id'          => $product->id,
                    'name'                => $product->name,
                    'product_sku'         => $product->sku,
                    'product_barcode'     => $product->barcode,

                    'variant_id'          => $variant->id,
                    'variant_name'        => $variant->name,
                    'variant_sku'         => $variant->sku,
                    'variant_barcode'     => $variant->barcode,

                    'price'               => $price ? (float) $price->price : 0,
                    'compare_at_price'    => $price ? (float) $price->compare_at_price : 0,
                    'cost_price'          => $price ? (float) $price->cost_price : 0,
                    'discount_percentage' => $price ? (float) $price->discount_percentage : 0,

                    'stock'               => (int) ($stock->quantity ?? 0),
                    'unit'                => $variant->unit ?? $product->unit,
                ];
            });
        });

    return response()->json([
        'data' => $products->values()
    ]);
}



    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $isHold = $request->action === 'hold';
    $oldOrderId = $request->old_order_id;

    /** ================= VALIDATION ================= */
    $request->validate([
        'store_id' => 'required|integer|exists:stores,id',
        'products' => 'required|array|min:1',
        'products.*.name' => 'required|string',
        'products.*.price' => 'required|numeric|min:0',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.product_id' => 'required|integer',
        'subtotal' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'tax_amount' => 'nullable|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'other_charges' => 'nullable|numeric|min:0',
        'customer_name' => 'required_if:customer_id,null|string|max:255',
        'customer_phone' => 'required_if:customer_id,null|digits:10',
        'payment_method' => $isHold ? 'nullable' : 'required|in:cash,card,upi,wallet',
    ]);

    $user = auth()->user();
    $selectedStoreId = (int) $request->store_id;

    // Super Admin check
    $isSuperAdmin = $user->hasRole('Super Admin');

    // Store authorization
    if (! $isSuperAdmin) {
        $allowedStoreIds = collect();
        if ($user->store_id) $allowedStoreIds->push($user->store_id);
        $allowedStoreIds = $allowedStoreIds->merge($user->stores()->pluck('stores.id'))->unique();

        if (! $allowedStoreIds->contains($selectedStoreId)) {
            abort(403, 'You are not allowed to use this store.');
        }
    }

    DB::beginTransaction();

    try {
        /** ================= CUSTOMER ================= */
        $customer = $request->customer_id
            ? Customer::findOrFail($request->customer_id)
            : Customer::create([
                'first_name' => explode(' ', $request->customer_name, 2)[0],
                'last_name' => explode(' ', $request->customer_name, 2)[1] ?? null,
                'phone' => $request->customer_phone,
                'email' => 'pos_'.$request->customer_phone.'@pos.local',
                'password' => bcrypt(Str::random(10)),
                'status' => 'active',
            ]);

        /** ================= ORDER CREATE / UPDATE ================= */
        if ($oldOrderId) {
            $order = Order::findOrFail($oldOrderId);
            $order->items()->delete();
            $order->update([
                'customer_id' => $customer->id,
                'store_id' => $selectedStoreId,
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount_amount' => $request->discount ?? 0,
                'shipping_charge' => $request->other_charges ?? 0,
                'total_amount' => $request->total,
                'total_items' => collect($request->products)->sum('quantity'),
                'status' => $isHold ? 'hold' : 'pending',
                'payment_status' => 'pending',
                'payment_method' => $isHold ? null : $request->payment_method,
            ]);
        } else {
            $order = Order::create([
                'order_number' => 'POS'.now()->format('Ymd').strtoupper(Str::random(6)),
                'customer_id' => $customer->id,
                'store_id' => $selectedStoreId,
                'order_source' => 'pos',
                'created_by' => $user->id,
                'status' => $isHold ? 'hold' : 'pending',
                'payment_status' => 'pending',
                'payment_method' => $isHold ? null : $request->payment_method,
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount_amount' => $request->discount ?? 0,
                'shipping_charge' => $request->other_charges ?? 0,
                'total_amount' => $request->total,
                'total_items' => collect($request->products)->sum('quantity'),
            ]);
        }

        /** ================= ORDER ITEMS & STOCK ================= */
        foreach ($request->products as $index => $product) {
            $item = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product['product_id'],
                'product_variant_id' => $product['variant_id'] ?? null,
                'product_name' => $product['name'],
                'variant_name' => $product['variant_name'] ?? null,
                'product_sku' => $product['sku'] ?? null,
                'quantity' => $product['quantity'],
                'unit_price' => $product['price'],
                'total_price' => $product['quantity'] * $product['price'],
                'status' => $isHold ? 'hold' : 'pending',
                'sort_order' => $index + 1,
            ]);

            // 🔹 Update stock only if order is NOT hold
            if (! $isHold && !empty($product['variant_id'])) {
                $stock = ProductStock::firstOrCreate(
                    ['product_variant_id' => $product['variant_id'], 'store_id' => $selectedStoreId],
                    ['quantity' => 0]
                );

                $stock->quantity = max(0, $stock->quantity - $product['quantity']);
                $stock->save();

                // // Optional: Stock movement log
                // ProductStock::create([
                //     'store_id' => $selectedStoreId,
                //     'product_variant_id' => $product['variant_id'],
                //     'quantity' => -$product['quantity'],
                //     'order_id' => $order->id,
                //     'type' => 'sale',
                //     'created_by' => $user->id,
                // ]);
            }
        }

        DB::commit();

        /** ================= RETURN ================= */
        if ($isHold) {
            return redirect()->route('new-order.index', 'pos')->with('success', 'Bill held successfully');
        }

        /** ================= PAYMENT ================= */
        if ($request->payment_method === 'cash') {
            Payment::create([
                'payment_number' => 'PAY'.uniqid(),
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'order_source' => 'pos',
                'payment_method' => 'cash',
                'status' => 'completed',
                'amount' => $order->total_amount,
                'paid_at' => now(),
            ]);

            $order->update([
                'status' => 'delivered',
                'payment_status' => 'paid',
            ]);

            $order->load('items', 'customer', 'store');
            $settings = AppSetting::first();

            return response(view('orders.invoice', compact('order', 'settings'))->render());
        }

        if (in_array($request->payment_method, ['card','upi','wallet'])) {
            $settings = AppSetting::getSettings();

            $api = new \Razorpay\Api\Api(trim($settings->razorpay_key), trim($settings->razorpay_secret));

            $rzpOrder = $api->order->create([
                'receipt' => $order->order_number,
                'amount' => (int) round($order->total_amount * 100),
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);

            $order->update(['gateway_order_id' => $rzpOrder['id']]);

            return view('payments.razorpay-checkout', [
                'order' => $order,
                'rzpOrder' => $rzpOrder,
                'razorpayKey' => trim($settings->razorpay_key),
            ]);
        }

    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
}




// public function invoice(Order $order)
// {
//     $order->load('items', 'customer', 'store'); // Load order items AND the customer
//     return view('orders.invoice', compact('order'));
// }

    /**
     * Display the specified resource.
     */
    public function show(string $type, string $id)
{
    $order = Order::with([
        'customer',
        'deliveryAddress',
        'items.product',
        'payments'
    ])->findOrFail($id);

    if ($type === 'pos') {
        return view('new-orders.pos.view', compact('order'));
    }

    return view('new-orders.online.view', compact('order'));
}

public function showonline(string $type, string $id)
{
    $order = Order::with([
        'customer',
        'deliveryAddress',
        'items.product',
        'payments'
    ])->findOrFail($id);

    if ($type === 'online') {
        return view('new-orders.online.statusview', compact('order'));
    }
}

    /**
     * Accept an online order.
     */
    public function accept(Request $request, string $id)
{
    $order = Order::findOrFail($id);

    // Prevent re-processing
    if ($order->status !== 'pending') {
        return redirect()
            ->route('new-order.index', 'online')
            ->with('error', 'Order already processed.');
    }

    $order->update([
        'status'       => 'confirmed',
        'confirmed_at' => now(),
    ]);

    // Generate invoice PDF
    $invoiceService = new InvoiceService();
    $fileName = $invoiceService->generateInvoice($order);
    if ($fileName) {
        $order->update(['file_name' => $fileName]);
    }

    return redirect()
        ->route('new-order.index', 'online')
        ->with('success', 'Order accepted successfully!');
}


    /**
     * Reject an online order.
     */
   public function reject(Request $request, string $id)
{
    $request->validate([
        'reject_reason' => 'required|string|min:10|max:500',
    ]);

    $order = Order::findOrFail($id);

    // Prevent re-processing
    if ($order->status !== 'pending') {
        return redirect()
            ->route('new-order.index', 'online')
            ->with('error', 'Order already processed.');
    }

    $order->update([
        'status'              => 'cancelled',
        'cancelled_at'        => now(),
        'cancellation_reason' => $request->reject_reason,
    ]);

    return redirect()
        ->route('new-order.index', 'online')
        ->with('success', 'Order rejected successfully!');
}


    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Order $order)
{
    // Allow only POS orders
    if ($order->order_source !== 'pos') {
        abort(404);
    }

    $user = auth()->user();
    $isSuperAdmin = $user->hasRole('Super Admin');

    // Authorization: non-super-admin cannot access other store orders
    if (! $isSuperAdmin) {
        $allowedStoreIds = collect();

        // User's primary store
        if ($user->store_id) {
            $allowedStoreIds->push($user->store_id);
        }

        // Stores from pivot table
        $pivotStoreIds = $user->stores()->pluck('stores.id');

        $allowedStoreIds = $allowedStoreIds
            ->merge($pivotStoreIds)
            ->unique();

        if (! $allowedStoreIds->contains($order->store_id)) {
            abort(403, 'You are not allowed to edit this order.');
        }
    }

    // Load relations for POS screen
    $order->load([
        'customer',
        'items.product',
        'items.productVariant',
        'store',
    ]);

    // Build stores list
    $storesQuery = Store::where('status', 1);

    if (! $isSuperAdmin) {
        $storesQuery->where(function ($q) use ($user) {
            $q->where('id', $user->store_id) // user's primary store
              ->orWhereHas('users', function ($uq) use ($user) { // pivot table
                  $uq->where('users.id', $user->id);
              });
        });
    }

    $stores = $storesQuery->orderBy('name')->get();

    // ✅ AUTO SELECT: order's store first, fallback to single available store
    $autoStoreId = $order->store_id ?? null;

    if (!$autoStoreId && $stores->count() === 1) {
        $autoStoreId = $stores->first()->id;
    }

    return view('new-orders.pos.create', compact('order', 'stores', 'autoStoreId'));
}




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function verify(Request $request)
{
    //  dd($request->all());
    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    try {
        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);
// dd('Signature Verified!');
        $order = Order::where('gateway_order_id', $request->razorpay_order_id)->firstOrFail();

        Payment::create([
            'payment_number' => 'PAY' . uniqid(),
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'payment_method' => $order->payment_method,
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => $request->razorpay_order_id,
            'gateway_transaction_id' => $request->razorpay_payment_id,
            'status' => 'pending',
            'amount' => $order->total_amount,
            'paid_at' => now(),
        ]);

        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'confirmed_at' => now(),
        ]);

        return redirect()->route('new-order.index','pos')
            ->with('success','Payment successful');

    } catch (SignatureVerificationError $e) {
// dd('Signature Verification Failed: ' . $e->getMessage());
        Order::where('gateway_order_id',$request->razorpay_order_id)
            ->update(['payment_status'=>'failed']);

        return redirect()->route('new-order.index','pos')
            ->with('error','Payment verification failed');
    }
}

public function webhook(Request $request)
{
    $signature = $request->header('X-Razorpay-Signature');
    $secret = config('services.razorpay.webhook_secret');

    abort_unless(
        hash_equals(hash_hmac('sha256', $request->getContent(), $secret), $signature),
        403
    );

    $payment = $request->payload['payment']['entity'];

    $order = Order::where('gateway_order_id', $payment['order_id'])->first();
    if (!$order) return response()->json();

    if ($payment['status'] === 'captured') {
        $order->update(['payment_status'=>'paid','status'=>'confirmed']);
    }

    if ($payment['status'] === 'failed') {
        $order->update(['payment_status'=>'failed']);
    }

    return response()->json(['success'=>true]);
}
public function refund(Order $order)
{
    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    $payment = $order->payments()
        ->where('status','completed')
        ->firstOrFail();

    $api->payment->fetch($payment->gateway_transaction_id)
        ->refund(['amount' => $payment->amount * 100]);

    $payment->update([
        'status' => 'refunded',
        'payment_type' => 'refund'
    ]);

    $order->update([
        'payment_status' => 'refunded',
        'status' => 'cancelled'
    ]);

    return back()->with('success','Refund initiated');
}

}