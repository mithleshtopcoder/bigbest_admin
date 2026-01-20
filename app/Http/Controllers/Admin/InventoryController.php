<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    /**
     * Store-Wise Stock Listing
     */
    public function storeWiseStock(Request $request)
    {
        if ($request->ajax()) {
            $storeId = $request->input('store_id');

            $query = Product::with(['variants.stocks']);

            // Only products that have stock
            $query->whereHas('variants.stocks', function ($q) use ($storeId) {
                $q->where('quantity', '>', 0);
                if (!empty($storeId)) {
                    $q->where('store_id', $storeId);
                }
            });

            $recordsTotal = (clone $query)->count();

            $search = $request->input('search.value');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%')
                      ->orWhere('barcode', 'like', '%' . $search . '%');
                });
            }

            $recordsFiltered = (clone $query)->count();

            $start = (int) $request->input('start', 0);
            $length = (int) $request->input('length', 25);

            $products = $query->skip($start)->take($length)->get();

            $data = $products->map(function ($product) use ($storeId) {
                $totalStock = 0;
                $variants = [];
                foreach ($product->variants as $variant) {
                    $variantStock = 0;
                    foreach ($variant->stocks as $stock) {
                        if (!empty($storeId) && (int) $stock->store_id !== (int) $storeId) {
                            continue;
                        }
                        $qty = (int) ($stock->quantity ?? 0);
                        $totalStock += $qty;
                        $variantStock += $qty;
                    }
                    if ($variantStock > 0) {
                        $variants[] = [
                            'name' => $variant->name ?? 'N/A',
                            'sku' => $variant->sku ?? 'N/A',
                        ];
                    }
                }

                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name ?? 'N/A',
                    'sku' => $product->sku ?? 'N/A',
                    'quantity' => $totalStock,
                    'variants' => $variants,
                    'actions' => '<a href="' . route('manage-product.product-master.edit', $product->id) . '" class="btn btn-sm btn-link text-primary" title="Edit Product"><i class="bi bi-pencil"></i></a>',
                ];
            });

            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.store-wise-stock.index', compact('stores'));
    }

    public function storeWiseStockProductStocks(Request $request, $productId)
    {
        $storeId = $request->input('store_id');

        $stocks = ProductStock::with(['productVariant', 'store'])
            ->whereHas('productVariant', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->when(!empty($storeId), function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->where('quantity', '>', 0)
            ->get();

        $data = $stocks->map(function ($stock) {
            return [
                'variant_name' => $stock->productVariant->name ?? 'N/A',
                'sku' => $stock->productVariant->sku ?? 'N/A',
                'store_name' => $stock->store->name ?? 'N/A',
                'quantity' => $stock->quantity ?? 0,
                'reserved_quantity' => $stock->reserved_quantity ?? 0,
                'available_quantity' => ($stock->quantity ?? 0) - ($stock->reserved_quantity ?? 0),
                'in_transit_quantity' => $stock->in_transit_quantity ?? 0,
                'pending_quantity' => $stock->pending_quantity ?? 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Stock In / Stock Out Listing
     */
    public function stockInStockOut(Request $request)
    {
        if ($request->ajax()) {
            $query = StockMovement::with(['productVariant.product', 'store', 'createdBy'])
                ->whereIn('movement_type', ['adjustment', 'damage', 'expiry', 'return']);

            // Filter by store
            if ($request->has('store_id') && $request->store_id) {
                $query->where('store_id', $request->store_id);
            }

            // Filter by movement type
            if ($request->has('movement_type') && $request->movement_type) {
                $query->where('movement_type', $request->movement_type);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('movement_date', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('movement_date', '<=', $request->date_to);
            }

            $recordsTotal = (clone $query)->count();

            $search = $request->input('search.value');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('productVariant', function ($vq) use ($search) {
                        $vq->where('name', 'like', '%' . $search . '%')
                           ->orWhere('sku', 'like', '%' . $search . '%')
                           ->orWhereHas('product', function ($pq) use ($search) {
                               $pq->where('name', 'like', '%' . $search . '%');
                           });
                    })
                    ->orWhereHas('store', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%' . $search . '%');
                    });
                });
            }

            $recordsFiltered = (clone $query)->count();

            $start = (int) $request->input('start', 0);
            $length = (int) $request->input('length', 25);
            $movements = $query->skip($start)->take($length)->get();

            $labels = [
                'adjustment' => 'Adjustment',
                'damage' => 'Damage',
                'expiry' => 'Expiry',
                'return' => 'Return',
            ];

            $data = $movements->map(function ($movement) use ($labels) {
                $quantity = (int) ($movement->quantity ?? 0);
                $sign = $quantity >= 0 ? '+' : '';
                $class = $quantity >= 0 ? 'text-success' : 'text-danger';

                return [
                    'movement_date' => $movement->movement_date
                        ? $movement->movement_date->format('Y-m-d')
                        : '-',
                    'product_name' => $movement->productVariant->product->name ?? 'N/A',
                    'variant_name' => $movement->productVariant->name ?? 'N/A',
                    'store_name' => $movement->store->name ?? 'N/A',
                    'movement_type_label' => $labels[$movement->movement_type] ?? ($movement->movement_type ?? '-'),
                    'quantity_display' => '<span class="' . $class . '">' . $sign . $quantity . '</span>',
                    'balance_after' => $movement->balance_after ?? '-',
                    'created_by_name' => $movement->createdBy->name ?? 'N/A',
                ];
            });

            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-in-stock-out.index', compact('stores'));
    }

    /**
     * Create Stock In / Stock Out
     */
    public function createStockInStockOut()
    {
        $stores = Store::where('status', 'active')->get();
        $products = \App\Models\Product::with(['variants'])->where('status', 'active')->get();

        return view('inventory-management.stock-in-stock-out.create', compact('stores','products'));
    }

    /**
     * Store Stock In / Stock Out
     */
    public function storeStockInStockOut(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'movement_type' => 'required|in:adjustment,damage,expiry,return',
            'movement_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                $quantity = $item['quantity'];
                $productVariantId = $item['product_variant_id'];
                $storeId = $validated['store_id'];

                // Get current stock
                $stock = ProductStock::where('product_variant_id', $productVariantId)
                    ->where('store_id', $storeId)
                    ->first();

                if (!$stock) {
                    $stock = ProductStock::create([
                        'product_variant_id' => $productVariantId,
                        'store_id' => $storeId,
                        'quantity' => 0,
                        'reserved_quantity' => 0,
                        'in_transit_quantity' => 0,
                        'pending_quantity' => 0,
                        'min_stock_level' => 0,
                        'stock_status' => 'out_of_stock',
                    ]);
                }

                // Update stock quantity
                $oldQuantity = $stock->quantity;
                $stock->quantity += $quantity;
                
                // Update stock status
                if ($stock->quantity <= 0) {
                    $stock->stock_status = 'out_of_stock';
                } elseif ($stock->quantity <= $stock->min_stock_level) {
                    $stock->stock_status = 'low_stock';
                } else {
                    $stock->stock_status = 'in_stock';
                }
                
                $stock->save();

                // Create stock movement record
                StockMovement::create([
                    'product_variant_id' => $productVariantId,
                    'store_id' => $storeId,
                    'movement_type' => $validated['movement_type'],
                    'quantity' => $quantity,
                    'balance_after' => $stock->quantity,
                    'reference_type' => 'stock_in_out',
                    'notes' => $item['notes'] ?? null,
                    'created_by' => auth()->id(),
                    'movement_date' => $validated['movement_date'],
                ]);
            }

            DB::commit();
            return redirect()->route('inventory-management.stock-in-stock-out')
                ->with('success', 'Stock movement recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error recording stock movement: ' . $e->getMessage());
        }
    }

    /**
     * Stock Adjustment Listing
     */
    public function stockAdjustment(Request $request)
    {
        if ($request->ajax()) {
            $query = StockAdjustment::with(['store', 'createdBy', 'approvedBy']);

            // Filter by store
            if ($request->has('store_id') && $request->store_id) {
                $query->where('store_id', $request->store_id);
            }

            // Filter by status
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by type
            if ($request->has('type') && $request->type) {
                $query->where('type', $request->type);
            }

            return DataTables::of($query)
                ->addColumn('store_name', function($adjustment) {
                    return $adjustment->store->name ?? 'N/A';
                })
                ->addColumn('type_badge', function($adjustment) {
                    $badges = [
                        'addition' => '<span class="badge bg-success">Addition</span>',
                        'reduction' => '<span class="badge bg-danger">Reduction</span>',
                        'correction' => '<span class="badge bg-info">Correction</span>',
                    ];
                    return $badges[$adjustment->type] ?? '<span class="badge bg-secondary">Unknown</span>';
                })
                ->addColumn('status_badge', function($adjustment) {
                    $badges = [
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                        'rejected' => '<span class="badge bg-danger">Rejected</span>',
                        'completed' => '<span class="badge bg-primary">Completed</span>',
                    ];
                    return $badges[$adjustment->status] ?? '<span class="badge bg-secondary">Unknown</span>';
                })
                ->addColumn('created_by_name', function($adjustment) {
                    return $adjustment->createdBy->name ?? 'N/A';
                })
                ->addColumn('approved_by_name', function($adjustment) {
                    return $adjustment->approvedBy->name ?? 'N/A';
                })
                ->addColumn('action', function($adjustment) {
                    $html = '<div class="btn-group">';
                    $html .= '<a href="' . route('inventory-management.stock-adjustment.view', $adjustment->id) . '" class="btn btn-sm btn-info">View</a>';
                    if ($adjustment->status == 'pending' && auth()->user()->can('approve_stock_adjustment')) {
                        $html .= '<button onclick="approveAdjustment(' . $adjustment->id . ')" class="btn btn-sm btn-success">Approve</button>';
                        $html .= '<button onclick="rejectAdjustment(' . $adjustment->id . ')" class="btn btn-sm btn-danger">Reject</button>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['type_badge', 'status_badge', 'action'])
                ->make(true);
        }

        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-adjustment.index', compact('stores'));
    }

    /**
     * Create Stock Adjustment
     */
    public function createStockAdjustment()
    {
        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-adjustment.create', compact('stores'));
    }

    /**
     * Store Stock Adjustment
     */
    public function storeStockAdjustment(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'type' => 'required|in:addition,reduction,correction',
            'reason' => 'required|in:damaged,expired,lost,stolen,found,returned,count_error,physical_count,vendor_return,promotion,opening_stock,other',
            'reason_description' => 'nullable|string',
            'adjustment_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.adjusted_quantity' => 'required|integer',
            'items.*.unit_cost' => 'nullable|numeric|min:0',
            'items.*.remark' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate adjustment number
            $adjustmentNumber = 'ADJ' . date('Ymd') . strtoupper(Str::random(6));

            $totalItems = count($validated['items']);
            $totalValue = 0;

            // Calculate total value
            foreach ($validated['items'] as $item) {
                $unitCost = $item['unit_cost'] ?? 0;
                $totalValue += abs($item['adjusted_quantity']) * $unitCost;
            }

            $adjustment = StockAdjustment::create([
                'adjustment_number' => $adjustmentNumber,
                'store_id' => $validated['store_id'],
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'reason_description' => $validated['reason_description'] ?? null,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'adjustment_date' => $validated['adjustment_date'],
                'created_by' => auth()->id(),
                'total_items' => $totalItems,
                'total_value' => $totalValue,
            ]);

            // Create adjustment items
            foreach ($validated['items'] as $index => $item) {
                $productVariantId = $item['product_variant_id'];
                $adjustedQuantity = $item['adjusted_quantity'];
                $unitCost = $item['unit_cost'] ?? 0;

                // Get current stock
                $stock = ProductStock::where('product_variant_id', $productVariantId)
                    ->where('store_id', $validated['store_id'])
                    ->first();

                $currentQuantity = $stock ? $stock->quantity : 0;
                $newQuantity = $currentQuantity + $adjustedQuantity;

                StockAdjustmentItem::create([
                    'stock_adjustment_id' => $adjustment->id,
                    'product_variant_id' => $productVariantId,
                    'current_quantity' => $currentQuantity,
                    'adjusted_quantity' => $adjustedQuantity,
                    'new_quantity' => $newQuantity,
                    'unit_cost' => $unitCost,
                    'total_value' => abs($adjustedQuantity) * $unitCost,
                    'remark' => $item['remark'] ?? null,
                    'sort_order' => $index,
                ]);
            }

            DB::commit();
            return redirect()->route('inventory-management.stock-adjustment')
                ->with('success', 'Stock adjustment created successfully. Waiting for approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating stock adjustment: ' . $e->getMessage());
        }
    }

    /**
     * View Stock Adjustment
     */
    public function viewStockAdjustment($id)
    {
        $adjustment = StockAdjustment::with(['store', 'createdBy', 'approvedBy', 'items.productVariant.product'])
            ->findOrFail($id);
        return view('inventory-management.stock-adjustment.view', compact('adjustment'));
    }

    /**
     * Approve Stock Adjustment
     */
    public function approveStockAdjustment($id)
    {
        DB::beginTransaction();
        try {
            $adjustment = StockAdjustment::with('items')->findOrFail($id);

            if ($adjustment->status != 'pending') {
                return response()->json(['success' => false, 'message' => 'Adjustment is not pending approval.']);
            }

            // Update stock for each item
            foreach ($adjustment->items as $item) {
                $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                    ->where('store_id', $adjustment->store_id)
                    ->first();

                if (!$stock) {
                    $stock = ProductStock::create([
                        'product_variant_id' => $item->product_variant_id,
                        'store_id' => $adjustment->store_id,
                        'quantity' => 0,
                        'reserved_quantity' => 0,
                        'in_transit_quantity' => 0,
                        'pending_quantity' => 0,
                        'min_stock_level' => 0,
                        'stock_status' => 'out_of_stock',
                    ]);
                }

                // Update quantity
                $stock->quantity = $item->new_quantity;

                // Update stock status
                if ($stock->quantity <= 0) {
                    $stock->stock_status = 'out_of_stock';
                } elseif ($stock->quantity <= $stock->min_stock_level) {
                    $stock->stock_status = 'low_stock';
                } else {
                    $stock->stock_status = 'in_stock';
                }

                $stock->save();

                // Create stock movement
                StockMovement::create([
                    'product_variant_id' => $item->product_variant_id,
                    'store_id' => $adjustment->store_id,
                    'movement_type' => 'adjustment',
                    'quantity' => $item->adjusted_quantity,
                    'balance_after' => $stock->quantity,
                    'reference_type' => 'stock_adjustment',
                    'reference_id' => $adjustment->id,
                    'notes' => $adjustment->notes,
                    'created_by' => auth()->id(),
                    'movement_date' => $adjustment->adjustment_date,
                ]);
            }

            $adjustment->update([
                'status' => 'completed',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Stock adjustment approved and completed successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error approving adjustment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject Stock Adjustment
     */
    public function rejectStockAdjustment(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $adjustment = StockAdjustment::findOrFail($id);

        if ($adjustment->status != 'pending') {
            return response()->json(['success' => false, 'message' => 'Adjustment is not pending approval.']);
        }

        $adjustment->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return response()->json(['success' => true, 'message' => 'Stock adjustment rejected successfully.']);
    }

    /**
     * Search Product Variants for Stock Operations
     */
   public function searchProductVariants(Request $request)
{
    $search = $request->input('search', '');
    $storeId = $request->input('store_id');

    $query = ProductVariant::with('product')
        ->where('is_active', true)
        ->whereHas('product', function($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', $search . '%'); // starts with search term
            }
        });

    // Also include variants whose name/SKU/barcode match
    if ($search) {
        $query->orWhere(function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('sku', 'like', '%' . $search . '%')
              ->orWhere('barcode', 'like', '%' . $search . '%');
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
            'current_stock' => $stock ? $stock->quantity : 0,
            'stock_status' => $stock ? $stock->stock_status : 'out_of_stock',
        ];
    });

    return response()->json(['variants' => $data]);
}

public function getProducts(Request $request)
{
    $storeId = $request->query('store_id');

    if (!$storeId) {
        return response()->json(['products' => []]);
    }

    // Fetch product variants available in this store
    $products = \App\Models\ProductVariant::whereHas('stocks', function($q) use ($storeId) {
        $q->where('store_id', $storeId)->where('quantity', '>', 0);
    })->get();

    // Format for dropdown
    $formatted = $products->map(function($variant) {
        return [
            'id' => $variant->id,
            'label' => $variant->product->name . ' - ' . $variant->name . ' (SKU: ' . $variant->sku . ')',
        ];
    });

    return response()->json(['products' => $formatted]);
}

}