<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\ProductStock;
use App\Models\CustomerAddress;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Notification;
use App\Events\NotificationEvent;
use App\Services\InvoiceService;
use App\Services\CartPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\MyHelper;

class OrderController extends Controller
{

    /**
     * Get all orders for authenticated customer
     */
    public function index(Request $request)
    {
        try {
            $customer = $request->user();

            $query = Order::where('customer_id', $customer->id)
                ->with([
                    'items.product',
                    'items.productVariant',
                    'deliveryAddress',
                    'store',
                    'coupon'
                ]);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by payment_status
            if ($request->has('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            // Filter by date range
            if ($request->has('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->has('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Search by order number
            if ($request->has('search')) {
                $query->where('order_number', 'like', '%' . $request->search . '%');
            }

            // Order by
            $orderBy = $request->get('order_by', 'created_at');
            $orderDirection = $request->get('order', 'desc');
            $query->orderBy($orderBy, $orderDirection);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $orders = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved successfully',
                'data' => $orders,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Create order from cart (Checkout)
     */
public function store(Request $request)
{
    // dd($request->all(), $request->header('content-type'), $request->user());

    $validator = Validator::make($request->all(), [
        'delivery_address_id' => 'required|exists:customer_addresses,id',
        'payment_method' => 'required|in:cash_on_delivery,online,wallet,card,upi,netbanking',
        'delivery_date' => 'nullable|date_format:Y-m-d',
        'delivery_time_slot' => 'nullable|date_format:H:i:s',
        'delivery_instructions' => 'nullable|string',
        'use_wallet' => 'nullable|boolean',
        'use_loyalty_points' => 'nullable|boolean',
        'coupon_code' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $customer = $request->user();

    $isOnlinePayment = in_array($request->payment_method, ['online', 'upi', 'card', 'netbanking']);
    $isWalletPayment = $request->payment_method === 'wallet';
    $isCOD = $request->payment_method === 'cash_on_delivery';

    // Address check
    $deliveryAddress = CustomerAddress::where('id', $request->delivery_address_id)
        ->where('customer_id', $customer->id)
        ->firstOrFail();

    // Cart
    $cartItems = Cart::where('customer_id', $customer->id)
        ->with(['productVariant.product', 'store'])
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Cart is empty'
        ], 400);
    }

    // Single store check
    $storeId = $cartItems->first()->store_id;
    foreach ($cartItems as $item) {
        if ($item->store_id !== $storeId) {
            return response()->json([
                'success' => false,
                'message' => 'All items must be from the same store'
            ], 400);
        }
    }

    DB::beginTransaction();
    try {

        // ---------------- PRICING (SERVICE) ----------------
        $pricingService = app(CartPricingService::class);
        $pricing = $pricingService->calculate($cartItems, $cartItems->first()->store);

        // Min order validation
        if (!$pricing['is_min_order_met']) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount is ₹' . $pricing['min_order_amount']
            ], 400);
        }

        $subtotal        = $pricing['subtotal'];
        $taxAmount       = $pricing['tax_amount'];
        $shippingCharge  = $pricing['shipping_charge'];

        // ---------------- COUPON ----------------
        $discountAmount = 0;
        $couponDiscount = 0;
        $couponId = null;

        if ($request->coupon_code) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                ->where('is_active', true)
                ->first();

            if ($coupon && $coupon->isValid($customer->id, $subtotal)) {
                $couponDiscount = $coupon->calculateDiscount($subtotal);
                $discountAmount += $couponDiscount;
                $couponId = $coupon->id;
            }
        }

        // ---------------- WALLET ----------------
        $walletAmountUsed = 0;
        if ($request->use_wallet && $customer->wallet_balance > 0) {
            $walletAmountUsed = min($customer->wallet_balance, $subtotal);
        }

        // ---------------- LOYALTY ----------------
        $loyaltyPointsUsed = 0;
        if ($request->use_loyalty_points && $customer->loyalty_points > 0) {
            $loyaltyPointsUsed = min(
                $customer->loyalty_points,
                ($subtotal - $walletAmountUsed) * 100
            );
        }

        // ---------------- TOTAL ----------------
        $totalAmount = $subtotal
            + $taxAmount
            + $shippingCharge
            - $discountAmount
            - $walletAmountUsed
            - ($loyaltyPointsUsed / 100);

        // ---------------- ORDER ----------------
        $order = Order::create([
            'order_number' => 'ORD' . date('Ymd') . strtoupper(uniqid()),
            'customer_id' => $customer->id,
            'store_id' => $storeId,
            'delivery_address_id' => $deliveryAddress->id,
            'order_source' => 'online',
            'status' => $isCOD ? 'pending' : ($isWalletPayment ? 'confirmed' : 'pending'),
            'payment_status' => $isOnlinePayment ? 'pending' : ($isWalletPayment ? 'paid' : 'pending'),
            'payment_method' => $request->payment_method,

            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_charge' => $shippingCharge,
            'discount_amount' => $discountAmount,
            'coupon_id' => $couponId,
            'wallet_amount_used' => $walletAmountUsed,
            'loyalty_points_used' => $loyaltyPointsUsed,
            'total_amount' => max(0, $totalAmount),

            'delivery_instructions' => $request->delivery_instructions,
            'delivery_date' => $request->delivery_date,
            'delivery_time_slot' => $request->delivery_time_slot,
            'total_items' => $cartItems->sum('quantity'),
        ]);

        // ---------------- ORDER ITEMS & STOCK ----------------
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->productVariant->product->id,
                'product_variant_id' => $cartItem->product_variant_id,
                'product_name' => $cartItem->productVariant->product->name,
                'variant_name' => $cartItem->productVariant->name,
                'product_sku' => $cartItem->productVariant->sku,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'total_price' => $cartItem->total_price,
                'tax_amount' => ($cartItem->total_price * $pricing['tax_percent']) / 100,
                'status' => 'pending',
               'vendor_id' => $cartItem->productVariant->product->vendor_id,

            ]);

            $stock = ProductStock::where('product_variant_id', $cartItem->product_variant_id)
                ->where('store_id', $storeId)
                ->lockForUpdate()
                ->first();

            if ($stock) {
                $stock->decrement('quantity', $cartItem->quantity);
                $stock->increment('reserved_quantity', $cartItem->quantity);
            }
        }

        // Wallet & loyalty deduction
        if ($walletAmountUsed > 0) $customer->decrement('wallet_balance', $walletAmountUsed);
        if ($loyaltyPointsUsed > 0) $customer->decrement('loyalty_points', $loyaltyPointsUsed);

        // Coupon usage
        if ($couponId && $couponDiscount > 0) {
            CouponUsage::create([
                'coupon_id' => $couponId,
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'discount_amount' => $couponDiscount,
                'order_amount' => $subtotal,
                'used_at' => now(),
            ]);
        }

        Cart::where('customer_id', $customer->id)->delete();

        DB::commit();

MyHelper::createNotification(
    $customer->id,
    'order',
    'Order Placed',
    'Your order #' . $order->order_number . ' has been placed successfully!',
    ['order_id' => $order->id, 'order_number' => $order->order_number],
    '/orders/' . $order->id
);


// ---------------- RETURN RESPONSE ----------------
return response()->json([
    'success' => true,
    'message' => 'Order placed successfully',
    'data' => [
        'order_id' => $order->id,
        'order_number' => $order->order_number,
        'total_amount' => $order->total_amount,
        'payment_status' => $order->payment_status,
        'status' => $order->status,
        'items' => $order->items->map(function ($item) {
            return [
                'product_name' => $item->product_name,
                'variant_name' => $item->variant_name,
                'quantity' => $item->quantity,
                'total_price' => $item->total_price,
                'vendor_id' => $item->vendor_id,
            ];
        }),
    ]
], 201);


    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Order failed: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Show single order details
     */
    public function show(Request $request, $id)
    {
        try {
            $customer = $request->user();

            $order = Order::where('id', $id)
                ->where('customer_id', $customer->id)
                ->with([
                    'items.product',
                    'items.productVariant',
                    'deliveryAddress',
                    'store',
                    'coupon',
                    'payments'
                ])
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order retrieved successfully',
                'data' => $order,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel an order
     * 
     * POST /api/v1/orders/{id}/cancel
     * 
     * Request Body (JSON):
     * {
     *     "cancellation_reason": "Changed my mind, want to cancel this order"
     * }
     */
    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $customer = $request->user();

            $order = Order::where('id', $id)
                ->where('customer_id', $customer->id)
                ->with(['items.productVariant', 'store'])
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Check if order can be cancelled
            $nonCancellableStatuses = ['delivered', 'cancelled', 'refunded'];
            if (in_array($order->status, $nonCancellableStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled. Order status: ' . $order->status
                ], 400);
            }

            // Check if order is already shipped/out for delivery
            if (in_array($order->status, ['shipped', 'out_for_delivery'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled as it has already been shipped. Please contact customer support for assistance.'
                ], 400);
            }

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_at' => now(),
            ]);

            // Update order items status
            $order->items()->update(['status' => 'cancelled']);

            // Release reserved stock back to available quantity
            foreach ($order->items as $item) {
                $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                    ->where('store_id', $order->store_id)
                    ->lockForUpdate()
                    ->first();

                if ($stock && $stock->reserved_quantity >= $item->quantity) {
                    // Release reserved quantity back to available
                    $stock->decrement('reserved_quantity', $item->quantity);
                    $stock->increment('quantity', $item->quantity);

                    // Update stock status
                    if ($stock->quantity > 0) {
                        if ($stock->quantity <= $stock->min_stock_level) {
                            $stock->update(['stock_status' => 'low_stock']);
                        } else {
                            $stock->update(['stock_status' => 'in_stock']);
                        }
                    }
                }
            }

            // Refund wallet amount if used
            if ($order->wallet_amount_used > 0) {
                $customer->increment('wallet_balance', $order->wallet_amount_used);
            }

            // Refund loyalty points if used
            if ($order->loyalty_points_used > 0) {
                $customer->increment('loyalty_points', $order->loyalty_points_used);
            }

            // Update payment status if order was paid
            if ($order->payment_status === 'paid') {
                // For online payments, mark as refunded (actual refund will be processed separately)
                if (in_array($order->payment_method, ['online', 'card', 'upi', 'netbanking'])) {
                    $order->update(['payment_status' => 'refunded']);
                } else {
                    // For COD, wallet, etc., just mark as cancelled
                    $order->update(['payment_status' => 'refunded']);
                }
            }

            // Create notification
            $notification = Notification::create([
                'customer_id' => $customer->id,
                'type' => 'order',
                'title' => 'Order Cancelled',
                'message' => 'Your order #' . $order->order_number . ' has been cancelled successfully.',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'refund_amount' => $order->wallet_amount_used > 0 || $order->loyalty_points_used > 0 
                        ? $order->total_amount 
                        : 0,
                ],
                'action_url' => "/orders/{$order->id}",
                'is_read' => false,
                'sent_at' => now(),
            ]);

            // Broadcast notification
            event(new NotificationEvent($notification));

            DB::commit();

            // Reload order with relationships
            $order->load(['items.product', 'items.productVariant', 'deliveryAddress', 'store', 'coupon']);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => [
                    'order' => $order,
                    'refund_info' => [
                        'wallet_refunded' => $order->wallet_amount_used,
                        'loyalty_points_refunded' => $order->loyalty_points_used,
                        'payment_refund_status' => $order->payment_status,
                        'note' => in_array($order->payment_method, ['online', 'card', 'upi', 'netbanking']) 
                            ? 'Refund will be processed to your original payment method within 5-7 business days.'
                            : 'Amount has been refunded to your wallet and loyalty points.',
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get invoice URL for an order
     */
    public function invoice(Request $request, $id)
    {
        try {
            $customer = $request->user();

            // Verify order belongs to the authenticated customer
            $order = Order::where('id', $id)
                ->where('customer_id', $customer->id)
                ->firstOrFail();

            // Check if invoice already exists
            if ($order->file_name && file_exists(public_path('documents/orders/' . $order->file_name))) {
                $invoiceUrl = url('documents/orders/' . $order->file_name);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice URL retrieved successfully',
                    'data' => [
                        'url' => $invoiceUrl,
                        'file_name' => $order->file_name,
                    ],
                ], 200);
            }

            // Generate invoice if it doesn't exist
            // Only generate if order is delivered or confirmed
            if (!in_array($order->status, ['delivered', 'confirmed', 'shipped', 'out_for_delivery'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice can only be generated for delivered or confirmed orders',
                ], 400);
            }

            // For API requests, generate synchronously for immediate response
            // But dispatch email job separately for better performance
            $invoiceService = new InvoiceService();
            $fileName = $invoiceService->generateInvoice($order);

            if (!$fileName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate invoice',
                ], 500);
            }

            // Update order with file name
            $order->update(['file_name' => $fileName]);

            // Dispatch email job asynchronously (non-blocking)
            if ($order->order_source === 'online') {
                $invoiceService->dispatchSendInvoiceEmail($order, $fileName);
            }

            $invoiceUrl = url('documents/orders/' . $fileName);

            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully',
                'data' => [
                    'url' => $invoiceUrl,
                    'file_name' => $fileName,
                ],
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or you do not have permission to access this order',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}