<?php

namespace App\Http\Controllers\Admin;
use App\Models\Option;
use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseReceiptItem;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\ProductVariant;
use App\Models\ProductStock;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    // ==================== PURCHASE ORDERS ====================
    
    /**
     * Display purchase orders list
     */
    public function index()
    {
        return view('procurement.purchase-orders.index');
    }

    /**
     * Get purchase orders for DataTables
     */
    public function getPurchaseOrders(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = PurchaseOrder::with(['supplier', 'store', 'creator']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = PurchaseOrder::count();
        $filteredRecords = $query->count();

        $orders = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $orders->map(function ($order) {
            $statusBadges = [
                'draft' => '<span class="badge bg-secondary">Draft</span>',
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'approved' => '<span class="badge bg-info">Approved</span>',
                'partially_received' => '<span class="badge bg-primary">Partially Received</span>',
                'received' => '<span class="badge bg-success">Received</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            ];

            return [
                'id' => $order->id,
                'po_number' => $order->po_number,
                'supplier' => $order->supplier->name ?? 'N/A',
                'store' => $order->store->name ?? 'N/A',
                'order_date' => $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A',
                'total_amount' => number_format($order->total_amount, 2),
                'status' => $statusBadges[$order->status] ?? '<span class="badge bg-secondary">' . $order->status . '</span>',
                'status_raw' => $order->status, // Raw status value for JavaScript conditions
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * DataTable method for purchase orders (alternative implementation)
     */
    public function datatable()
    {
        $pos = PurchaseOrder::query();

        return DataTables::of($pos)
            ->addColumn('po_number', fn($p) => '
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-text"><i class="feather-file-text"></i></div>
                    <strong>#PO-'.$p->id.'</strong>
                </div>
            ')
            ->addColumn('supplier', fn($p) => $p->supplier_name)
            ->addColumn('order_date', fn($p) => $p->order_date)
            ->addColumn('expected_date', fn($p) => $p->expected_date)
            ->addColumn('total_amount', fn($p) => '₹'.number_format($p->total_amount, 2))
            ->addColumn('status', fn($p) => '
                <span class="badge bg-soft-'.$p->status_color.'">'.$p->status.'</span>
            ')
            ->addColumn('action', fn($p) => '
                <div class="d-flex justify-content-end gap-2">
                    <a href="#" class="avatar-text avatar-sm"><i class="feather-eye"></i></a>
                    <a href="#" class="avatar-text avatar-sm"><i class="feather-edit"></i></a>
                </div>
            ')
            ->rawColumns(['po_number','status','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new purchase order
     */
    public function createPo()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $stores = Store::where('status', 'active')->get();
        return view('procurement.purchase-orders.create', compact('suppliers', 'stores'));
    }

    /**
     * Search product variants for purchase order
     */
    public function searchProductVariants(Request $request)
    {
        $search = $request->input('search', '');
        $storeId = $request->input('store_id');

        $query = ProductVariant::with(['product.category', 'product.subCategory'])
            ->where('is_active', true);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('barcode', 'like', '%' . $search . '%')
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $variants = $query->limit(50)->get();

        $data = $variants->map(function ($variant) use ($storeId) {
            $stock = null;
            if ($storeId) {
                $stock = ProductStock::where('product_variant_id', $variant->id)
                    ->where('store_id', $storeId)
                    ->first();
            }

            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'product_name' => $variant->product->name ?? 'N/A',
                'sku' => $variant->sku ?? 'N/A',
                'barcode' => $variant->barcode ?? 'N/A',
                'unit' => $variant->unit ?? 'piece',
                'cost_price' => $variant->cost_price ?? 0,
                'mrp' => $variant->mrp ?? 0,
                'selling_price' => $variant->selling_price ?? 0,
                'current_stock' => $stock ? $stock->quantity : 0,
                'stock_status' => $stock ? $stock->stock_status : 'out_of_stock',
            ];
        });

        return response()->json(['variants' => $data]);
    }

    /**
     * Store a newly created purchase order
     */
    public function storePo(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'store_id' => 'required|exists:stores,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.ordered_quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Generate PO number
            $poNumber = 'PO' . date('Ymd') . strtoupper(Str::random(6));

            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            // Calculate totals
            foreach ($validated['items'] as $item) {
                $quantity = $item['ordered_quantity'];
                $unitCost = $item['unit_cost'];
                $taxRate = $item['tax_rate'] ?? 0;
                $discountPercent = $item['discount_percentage'] ?? 0;

                $itemTotal = $quantity * $unitCost;
                $discountAmount = ($itemTotal * $discountPercent) / 100;
                $itemTotalAfterDiscount = $itemTotal - $discountAmount;
                $taxAmount = ($itemTotalAfterDiscount * $taxRate) / 100;

                $subtotal += $itemTotal;
                $totalDiscount += $discountAmount;
                $totalTax += $taxAmount;
            }

            $totalAmount = $subtotal - $totalDiscount + $totalTax;

            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $validated['supplier_id'],
                'store_id' => $validated['store_id'],
                'status' => 'draft',
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'total_amount' => $totalAmount,
                'created_by' => auth()->id(),
                'total_items' => count($validated['items']),
            ]);

            // Create items and update pending stock
            foreach ($validated['items'] as $index => $item) {
                $quantity = $item['ordered_quantity'];
                $unitCost = $item['unit_cost'];
                $taxRate = $item['tax_rate'] ?? 0;
                $discountPercent = $item['discount_percentage'] ?? 0;

                $itemTotal = $quantity * $unitCost;
                $discountAmount = ($itemTotal * $discountPercent) / 100;
                $itemTotalAfterDiscount = $itemTotal - $discountAmount;
                $taxAmount = ($itemTotalAfterDiscount * $taxRate) / 100;
                $totalCost = $itemTotalAfterDiscount + $taxAmount;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'ordered_quantity' => $quantity,
                    'received_quantity' => 0,
                    'pending_quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'discount_percentage' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'sort_order' => $index,
                ]);

                // Update pending_quantity in ProductStock
                $stock = ProductStock::where('product_variant_id', $item['product_variant_id'])
                    ->where('store_id', $validated['store_id'])
                    ->first();

                if ($stock) {
                    $stock->pending_quantity += $quantity;
                    $stock->save();
                } else {
                    ProductStock::create([
                        'product_variant_id' => $item['product_variant_id'],
                        'store_id' => $validated['store_id'],
                        'quantity' => 0,
                        'reserved_quantity' => 0,
                        'in_transit_quantity' => 0,
                        'pending_quantity' => $quantity,
                        'min_stock_level' => 0,
                        'stock_status' => 'out_of_stock',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('procurement.purchase-orders')
                ->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Show purchase order details
     */
    public function view($id)
    {
        $order = PurchaseOrder::with(['supplier', 'store', 'items.productVariant.product', 'creator', 'approver'])
            ->findOrFail($id);
        return view('procurement.purchase-orders.view', compact('order'));
    }

    /**
     * Get purchase order items (for GRN creation)
     */
    public function getPoItems($id)
    {
        $order = PurchaseOrder::with(['items.productVariant.product'])->findOrFail($id);
        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_variant_id' => $item->product_variant_id,
                'product_name' => $item->productVariant->product->name ?? 'N/A',
                'variant_name' => $item->productVariant->name ?? 'N/A',
                'ordered_quantity' => $item->ordered_quantity,
                'received_quantity' => $item->received_quantity,
                'pending_quantity' => $item->pending_quantity,
                'unit_cost' => $item->unit_cost,
            ];
        });

        return response()->json(['items' => $items]);
    }

    /**
     * Approve purchase order
     */
    public function approvePo($id)
    {
        try {
            $order = PurchaseOrder::findOrFail($id);
            $order->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Purchase order approved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error approving purchase order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cancel purchase order and update pending stock
     */
    public function cancelPo($id)
    {
        DB::beginTransaction();
        try {
            $order = PurchaseOrder::with('items')->findOrFail($id);
            
            if ($order->status == 'cancelled') {
                return response()->json(['success' => false, 'message' => 'Purchase order is already cancelled.'], 400);
            }

            // Reduce pending_quantity in ProductStock for each item
            foreach ($order->items as $item) {
                $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                    ->where('store_id', $order->store_id)
                    ->first();

                if ($stock && $item->pending_quantity > 0) {
                    $stock->pending_quantity = max(0, $stock->pending_quantity - $item->pending_quantity);
                    $stock->save();
                }
            }

            $order->update([
                'status' => 'cancelled',
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Purchase order cancelled successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error cancelling purchase order: ' . $e->getMessage()], 500);
        }
    }

    // ==================== GRN (PURCHASE RECEIPTS) ====================

    /**
     * Display GRN list
     */
    public function grn()
    {
        return view('procurement.grn.index');
    }

    /**
     * Get GRN for DataTables
     */
    public function getGrn(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = PurchaseReceipt::with(['supplier', 'store', 'purchaseOrder']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', '%' . $search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = PurchaseReceipt::count();
        $filteredRecords = $query->count();

        $receipts = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $receipts->map(function ($receipt) {
            $statusBadges = [
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'received' => '<span class="badge bg-success">Received</span>',
                'partially_received' => '<span class="badge bg-info">Partially Received</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            ];

            return [
                'id' => $receipt->id,
                'receipt_number' => $receipt->receipt_number,
                'po_number' => $receipt->purchaseOrder->po_number ?? 'N/A',
                'supplier' => $receipt->supplier->name ?? 'N/A',
                'receipt_date' => $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d M Y') : 'N/A',
                'total_amount' => number_format($receipt->total_amount, 2),
                'status' => $statusBadges[$receipt->status] ?? '<span class="badge bg-secondary">' . $receipt->status . '</span>',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show the form for creating a new GRN
     */
    public function createGrn()
    {
        $purchaseOrders = PurchaseOrder::where('status', 'approved')
            ->with(['supplier', 'store'])
            ->get();
        return view('procurement.grn.create', compact('purchaseOrders'));
    }

    /**
     * Store a newly created GRN
     */
    public function storeGrn(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'receipt_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'invoice_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.received_quantity' => 'required|integer|min:0',
            'items.*.damaged_quantity' => 'nullable|integer|min:0',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.expiry_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::with('items')->findOrFail($validated['purchase_order_id']);

            // Generate receipt number
            $receiptNumber = 'GRN' . date('Ymd') . strtoupper(Str::random(6));

            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            // Calculate totals
            foreach ($validated['items'] as $item) {
                $poItem = PurchaseOrderItem::findOrFail($item['purchase_order_item_id']);
                $receivedQty = $item['received_quantity'];
                $damagedQty = $item['damaged_quantity'] ?? 0;
                $acceptedQty = $receivedQty - $damagedQty;
                $unitCost = $item['unit_cost'] ?? $poItem->unit_cost;

                $itemTotal = $acceptedQty * $unitCost;
                $taxAmount = ($itemTotal * $poItem->tax_rate) / 100;
                $discountAmount = ($itemTotal * $poItem->discount_percentage) / 100;

                $subtotal += $itemTotal;
                $totalTax += $taxAmount;
                $totalDiscount += $discountAmount;
            }

            $totalAmount = $subtotal - $totalDiscount + $totalTax;

            $receipt = PurchaseReceipt::create([
                'receipt_number' => $receiptNumber,
                'purchase_order_id' => $validated['purchase_order_id'],
                'supplier_id' => $purchaseOrder->supplier_id,
                'store_id' => $purchaseOrder->store_id,
                'status' => 'received',
                'receipt_date' => $validated['receipt_date'],
                'invoice_number' => $validated['invoice_number'] ?? null,
                'invoice_date' => $validated['invoice_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'total_amount' => $totalAmount,
                'received_by' => auth()->id(),
                'total_items' => count($validated['items']),
            ]);

            // Create receipt items and update stock
            foreach ($validated['items'] as $index => $item) {
                $poItem = PurchaseOrderItem::findOrFail($item['purchase_order_item_id']);
                $receivedQty = $item['received_quantity'];
                $damagedQty = $item['damaged_quantity'] ?? 0;
                $acceptedQty = $receivedQty - $damagedQty;
                $unitCost = $item['unit_cost'] ?? $poItem->unit_cost;

                $itemTotal = $acceptedQty * $unitCost;
                $taxAmount = ($itemTotal * $poItem->tax_rate) / 100;
                $discountAmount = ($itemTotal * $poItem->discount_percentage) / 100;
                $totalCost = $itemTotal - $discountAmount + $taxAmount;

                $receiptItem = PurchaseReceiptItem::create([
                    'purchase_receipt_id' => $receipt->id,
                    'purchase_order_item_id' => $poItem->id,
                    'product_variant_id' => $poItem->product_variant_id,
                    'received_quantity' => $receivedQty,
                    'damaged_quantity' => $damagedQty,
                    'returned_quantity' => 0,
                    'accepted_quantity' => $acceptedQty,
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'expiry_date' => $item['expiry_date'] ?? null,
                    'sort_order' => $index,
                ]);

                // Update purchase order item received quantity
                $poItem->received_quantity += $acceptedQty;
                $poItem->pending_quantity = $poItem->ordered_quantity - $poItem->received_quantity;
                $poItem->save();

                // Update stock
                $stock = ProductStock::where('product_variant_id', $poItem->product_variant_id)
                    ->where('store_id', $purchaseOrder->store_id)
                    ->first();

                if ($stock) {
                    // Reduce pending quantity and add to actual quantity
                    $stock->pending_quantity = max(0, $stock->pending_quantity - $acceptedQty);
                    $stock->quantity += $acceptedQty;
                    $stock->last_restocked_at = now();
                    $stock->last_restocked_by = auth()->id();
                    
                    // Update stock status based on quantity vs min_stock_level
                    if ($stock->quantity <= 0) {
                        $stock->stock_status = 'out_of_stock';
                    } elseif ($stock->quantity <= $stock->min_stock_level) {
                        $stock->stock_status = 'low_stock';
                    } else {
                        $stock->stock_status = 'in_stock';
                    }
                    
                    $stock->save();
                } else {
                    ProductStock::create([
                        'product_variant_id' => $poItem->product_variant_id,
                        'store_id' => $purchaseOrder->store_id,
                        'quantity' => $acceptedQty,
                        'reserved_quantity' => 0,
                        'in_transit_quantity' => 0,
                        'pending_quantity' => 0,
                        'min_stock_level' => 0,
                        'stock_status' => $acceptedQty > 0 ? 'in_stock' : 'out_of_stock',
                        'last_restocked_at' => now(),
                        'last_restocked_by' => auth()->id(),
                    ]);
                }

                // Update cost_price in product_variant (weighted average or latest)
                $productVariant = ProductVariant::find($poItem->product_variant_id);
                if ($productVariant) {
                    // Update cost price with the latest purchase cost
                    $productVariant->cost_price = $unitCost;
                    $productVariant->save();

                    // Create price history record
                    ProductPrice::create([
                        'product_variant_id' => $productVariant->id,
                        'price' => $productVariant->selling_price ?? $unitCost,
                        'compare_at_price' => $productVariant->mrp,
                        'cost_price' => $unitCost,
                        'effective_from' => now(),
                        'is_active' => true,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            // Update purchase order status
            $allReceived = $purchaseOrder->items()->whereColumn('received_quantity', '<', 'ordered_quantity')->count() == 0;
            $someReceived = $purchaseOrder->items()->where('received_quantity', '>', 0)->count() > 0;

            if ($allReceived) {
                $purchaseOrder->status = 'received';
            } elseif ($someReceived) {
                $purchaseOrder->status = 'partially_received';
            }
            $purchaseOrder->save();

            DB::commit();

            return redirect()->route('procurement.grn')
                ->with('success', 'GRN created successfully and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating GRN: ' . $e->getMessage());
        }
    }

    // ==================== PURCHASE INVOICES ====================

    /**
     * Display purchase invoices list
     */
    public function invoices()
    {
        return view('procurement.purchase-invoices.index');
    }

    /**
     * Get purchase invoices for DataTables
     */
    public function getInvoices(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = PurchaseInvoice::with(['supplier', 'store']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('supplier_invoice_number', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = PurchaseInvoice::count();
        $filteredRecords = $query->count();

        $invoices = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $invoices->map(function ($invoice) {
            $statusBadges = [
                'draft' => '<span class="badge bg-secondary">Draft</span>',
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'approved' => '<span class="badge bg-info">Approved</span>',
                'paid' => '<span class="badge bg-success">Paid</span>',
                'partially_paid' => '<span class="badge bg-primary">Partially Paid</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            ];

            return [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'supplier' => $invoice->supplier->name ?? 'N/A',
                'invoice_date' => $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : 'N/A',
                'total_amount' => number_format($invoice->total_amount, 2),
                'paid_amount' => number_format($invoice->paid_amount, 2),
                'pending_amount' => number_format($invoice->pending_amount, 2),
                'status' => $statusBadges[$invoice->status] ?? '<span class="badge bg-secondary">' . $invoice->status . '</span>',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show the form for creating a new purchase invoice
     */
    public function createInvoice()
    {
        $receipts = PurchaseReceipt::where('status', 'received')
            ->with(['supplier', 'store', 'purchaseOrder'])
            ->get();
        $suppliers = Supplier::where('status', 'active')->get();
        $stores = Store::where('status', 'active')->get();
        return view('procurement.purchase-invoices.create', compact('receipts', 'suppliers', 'stores'));
    }

    /**
     * Store a newly created purchase invoice
     */
    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'purchase_receipt_id' => 'nullable|exists:purchase_receipts,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'store_id' => 'required|exists:stores,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'supplier_invoice_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Generate invoice number
            $invoiceNumber = 'PINV' . date('Ymd') . strtoupper(Str::random(6));

            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            // Calculate totals
            foreach ($validated['items'] as $item) {
                $quantity = $item['quantity'];
                $unitCost = $item['unit_cost'];
                $taxRate = $item['tax_rate'] ?? 0;
                $discountPercent = $item['discount_percentage'] ?? 0;

                $itemTotal = $quantity * $unitCost;
                $discountAmount = ($itemTotal * $discountPercent) / 100;
                $itemTotalAfterDiscount = $itemTotal - $discountAmount;
                $taxAmount = ($itemTotalAfterDiscount * $taxRate) / 100;

                $subtotal += $itemTotal;
                $totalDiscount += $discountAmount;
                $totalTax += $taxAmount;
            }

            $totalAmount = $subtotal - $totalDiscount + $totalTax;

            $invoice = PurchaseInvoice::create([
                'invoice_number' => $invoiceNumber,
                'purchase_order_id' => null,
                'purchase_receipt_id' => $validated['purchase_receipt_id'] ?? null,
                'supplier_id' => $validated['supplier_id'],
                'store_id' => $validated['store_id'],
                'status' => 'draft',
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'] ?? null,
                'supplier_invoice_number' => $validated['supplier_invoice_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'shipping_charge' => 0,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'pending_amount' => $totalAmount,
                'payment_status' => 'pending',
                'created_by' => auth()->id(),
                'total_items' => count($validated['items']),
            ]);

            // Create items
            foreach ($validated['items'] as $index => $item) {
                $quantity = $item['quantity'];
                $unitCost = $item['unit_cost'];
                $taxRate = $item['tax_rate'] ?? 0;
                $discountPercent = $item['discount_percentage'] ?? 0;

                $itemTotal = $quantity * $unitCost;
                $discountAmount = ($itemTotal * $discountPercent) / 100;
                $itemTotalAfterDiscount = $itemTotal - $discountAmount;
                $taxAmount = ($itemTotalAfterDiscount * $taxRate) / 100;
                $totalCost = $itemTotalAfterDiscount + $taxAmount;

                PurchaseInvoiceItem::create([
                    'purchase_invoice_id' => $invoice->id,
                    'purchase_receipt_item_id' => null,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'discount_percentage' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'sort_order' => $index,
                ]);
            }

            DB::commit();

            return redirect()->route('procurement.purchase-invoices')
                ->with('success', 'Purchase invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating purchase invoice: ' . $e->getMessage());
        }
    }

    // ==================== PURCHASE RETURNS ====================

    /**
     * Display purchase returns list
     */
    public function returns()
    {
        return view('procurement.purchase-returns.index');
    }

    /**
     * Get purchase returns for DataTables
     */
    public function getReturns(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = PurchaseReturn::with(['supplier', 'store']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', '%' . $search . '%')
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = PurchaseReturn::count();
        $filteredRecords = $query->count();

        $returns = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $returns->map(function ($return) {
            $statusBadges = [
                'draft' => '<span class="badge bg-secondary">Draft</span>',
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'approved' => '<span class="badge bg-info">Approved</span>',
                'processed' => '<span class="badge bg-success">Processed</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            ];

            return [
                'id' => $return->id,
                'return_number' => $return->return_number,
                'supplier' => $return->supplier->name ?? 'N/A',
                'return_date' => $return->return_date ? \Carbon\Carbon::parse($return->return_date)->format('d M Y') : 'N/A',
                'return_type' => ucfirst(str_replace('_', ' ', $return->return_type)),
                'total_amount' => number_format($return->total_amount, 2),
                'status' => $statusBadges[$return->status] ?? '<span class="badge bg-secondary">' . $return->status . '</span>',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show the form for creating a new purchase return
     */
    public function createReturn()
    {
        $receipts = PurchaseReceipt::where('status', 'received')
            ->with(['supplier', 'store', 'items.productVariant'])
            ->get();
        $suppliers = Supplier::where('status', 'active')->get();
        $stores = Store::where('status', 'active')->get();
        return view('procurement.purchase-returns.create', compact('receipts', 'suppliers', 'stores'));
    }

  public function getProductsByStore(Request $request)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
    ]);

    $items = PurchaseReceiptItem::with([
        'productVariant.product',
        'purchaseReceipt' // ✅ FIXED
    ])
    ->whereHas('purchaseReceipt', function ($q) use ($request) {
        $q->where('store_id', $request->store_id)
          ->where('status', 'received');
    })
    ->whereColumn('accepted_quantity', '>', 'returned_quantity')
    ->get();

    return response()->json([
        'products' => $items->map(function ($item) {
            return [
                'id' => $item->product_variant_id,
                'label' =>
                    $item->productVariant->product->name .
                    ($item->productVariant->name ? ' - ' . $item->productVariant->name : ''),
                'available_qty' =>
                    $item->accepted_quantity - $item->returned_quantity,
                'unit_cost' => $item->unit_cost,
                'receipt_item_id' => $item->id,
            ];
        })
    ]);
}


public function getGrnItems(Request $request)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'store_id'    => 'required|exists:stores,id',
    ]);

    $items = PurchaseReceiptItem::with([
            'productVariant:id,name',
            'purchaseReceipt:id,receipt_number'
        ])
        ->whereHas('purchaseReceipt', function ($q) use ($request) {
            $q->where('supplier_id', $request->supplier_id)
              ->where('store_id', $request->store_id);
        })
        ->whereRaw('accepted_quantity > returned_quantity')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'product_variant_id' => $item->product_variant_id,
                'variant_name' => $item->productVariant->name,
                'grn_no' => $item->purchaseReceipt->receipt_number,
                'unit_cost' => $item->unit_cost,
                'tax_rate' => $item->purchaseOrderItem->tax_rate ?? 0,
                'available_qty' => $item->accepted_quantity - $item->returned_quantity,
            ];
        });

    return response()->json(['items' => $items]);
}


    /**
     * Store a newly created purchase return
     */
    public function storeReturn(Request $request)
    {
        $validated = $request->validate([
            'purchase_receipt_id' => 'nullable|exists:purchase_receipts,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'store_id' => 'required|exists:stores,id',
            'return_type' => 'required|in:damaged,defective,wrong_item,excess,other',
            'return_date' => 'required|date',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.purchase_receipt_item_id' => 'nullable|exists:purchase_receipt_items,id',
            'items.*.returned_quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Generate return number
            $returnNumber = 'PRET' . date('Ymd') . strtoupper(Str::random(6));

            $subtotal = 0;
            $totalTax = 0;

            // Calculate totals
            foreach ($validated['items'] as $item) {
                $quantity = $item['returned_quantity'];
                $unitCost = $item['unit_cost'];
                $taxRate = $item['tax_rate'] ?? 0;

                $itemTotal = $quantity * $unitCost;
                $taxAmount = ($itemTotal * $taxRate) / 100;

                $subtotal += $itemTotal;
                $totalTax += $taxAmount;
            }

            $totalAmount = $subtotal + $totalTax;

            $purchaseReturn = PurchaseReturn::create([
                'return_number' => $returnNumber,
                'purchase_order_id' => null,
                'purchase_receipt_id' => $validated['purchase_receipt_id'] ?? null,
                'purchase_invoice_id' => null,
                'supplier_id' => $validated['supplier_id'],
                'store_id' => $validated['store_id'],
                'status' => 'draft',
                'return_type' => $validated['return_type'],
                'return_date' => $validated['return_date'],
                'reason' => $validated['reason'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'refund_status' => 'pending',
                'refunded_amount' => 0,
                'created_by' => auth()->id(),
                'total_items' => count($validated['items']),
            ]);

            // Create items and update stock
            foreach ($validated['items'] as $index => $item) {
                $quantity = $item['returned_quantity'];
                $unitCost = $item['unit_cost'];
                $taxRate = $item['tax_rate'] ?? 0;

                $itemTotal = $quantity * $unitCost;
                $taxAmount = ($itemTotal * $taxRate) / 100;
                $totalCost = $itemTotal + $taxAmount;

                PurchaseReturnItem::create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'purchase_receipt_item_id' => $item['purchase_receipt_item_id'] ?? null,
                    'product_variant_id' => $item['product_variant_id'],
                    'returned_quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'reason' => $item['reason'] ?? null,
                    'sort_order' => $index,
                ]);

                // Update stock (reduce quantity)
                $stock = ProductStock::where('product_variant_id', $item['product_variant_id'])
                    ->where('store_id', $validated['store_id'])
                    ->first();

                if ($stock && $stock->quantity >= $quantity) {
                    $stock->quantity -= $quantity;
                    
                    // Update stock status based on quantity vs min_stock_level
                    if ($stock->quantity <= 0) {
                        $stock->stock_status = 'out_of_stock';
                    } elseif ($stock->quantity <= $stock->min_stock_level) {
                        $stock->stock_status = 'low_stock';
                    } else {
                        $stock->stock_status = 'in_stock';
                    }
                    
                    $stock->save();
                }
            }

            DB::commit();

            return redirect()->route('procurement.purchase-returns')
                ->with('success', 'Purchase return created successfully and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating purchase return: ' . $e->getMessage());
        }
    }
}