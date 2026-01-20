<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TransferController extends Controller
{
    /**
     * Transfer Request Listing
     */
    public function request(Request $request)
    {
        if ($request->ajax()) {
            $query = StockTransfer::with(['fromStore', 'toStore', 'requestedBy'])
                ->where('status', 'pending');

            // Filter by from store
            if ($request->has('from_store_id') && $request->from_store_id) {
                $query->where('from_store_id', $request->from_store_id);
            }

            // Filter by to store
            if ($request->has('to_store_id') && $request->to_store_id) {
                $query->where('to_store_id', $request->to_store_id);
            }

            return DataTables::of($query)
                ->addColumn('from_store_name', function($transfer) {
                    return $transfer->fromStore->name ?? 'N/A';
                })
                ->addColumn('to_store_name', function($transfer) {
                    return $transfer->toStore->name ?? 'N/A';
                })
                ->addColumn('requested_by_name', function($transfer) {
                    return $transfer->requestedBy->name ?? 'N/A';
                })
                ->addColumn('status_badge', function($transfer) {
                    $badges = [
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                        'in_transit' => '<span class="badge bg-info">In Transit</span>',
                        'received' => '<span class="badge bg-primary">Received</span>',
                        'completed' => '<span class="badge bg-success">Completed</span>',
                        'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                    ];
                    return $badges[$transfer->status] ?? '<span class="badge bg-secondary">Unknown</span>';
                })
                ->addColumn('action', function($transfer) {
                    $html = '<div class="btn-group">';
                    $html .= '<a href="' . route('inventory-management.stock-transfer.transfer-request.view', $transfer->id) . '" class="btn btn-sm btn-info">View</a>';
                    if ($transfer->status == 'pending') {
                        $html .= '<a href="' . route('inventory-management.stock-transfer.transfer-request.edit', $transfer->id) . '" class="btn btn-sm btn-warning">Edit</a>';
                        $html .= '<button onclick="cancelTransfer(' . $transfer->id . ')" class="btn btn-sm btn-danger">Cancel</button>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-transfer.transfer-request.index', compact('stores'));
    }

    /**
     * Create Transfer Request
     */
    public function createRequest()
    {
        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-transfer.transfer-request.create', compact('stores'));
    }

    /**
     * Store Transfer Request
     */
    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'from_store_id' => 'required|exists:stores,id',
            'to_store_id' => 'required|exists:stores,id|different:from_store_id',
            'transfer_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:transfer_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.requested_quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate transfer number
            $transferNumber = 'TRF' . date('Ymd') . strtoupper(Str::random(6));

            $totalItems = count($validated['items']);
            $totalQuantity = array_sum(array_column($validated['items'], 'requested_quantity'));

            $transfer = StockTransfer::create([
                'transfer_number' => $transferNumber,
                'from_store_id' => $validated['from_store_id'],
                'to_store_id' => $validated['to_store_id'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'transfer_date' => $validated['transfer_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'requested_by' => auth()->id(),
                'total_items' => $totalItems,
                'total_quantity' => $totalQuantity,
            ]);

            // Create transfer items
            foreach ($validated['items'] as $index => $item) {
                StockTransferItem::create([
                    'stock_transfer_id' => $transfer->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'requested_quantity' => $item['requested_quantity'],
                    'approved_quantity' => null,
                    'transferred_quantity' => 0,
                    'received_quantity' => 0,
                    'damaged_quantity' => 0,
                    'notes' => $item['notes'] ?? null,
                    'sort_order' => $index,
                ]);
            }

            DB::commit();
            return redirect()->route('inventory-management.stock-transfer.transfer-request')
                ->with('success', 'Transfer request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating transfer request: ' . $e->getMessage());
        }
    }

    /**
     * View Transfer Request
     */
    public function viewRequest($id)
    {
        $transfer = StockTransfer::with([
            'fromStore', 
            'toStore', 
            'requestedBy', 
            'approvedBy', 
            'receivedBy',
            'items.productVariant.product'
        ])->findOrFail($id);
        
        return view('inventory-management.stock-transfer.transfer-request.view', compact('transfer'));
    }

    /**
     * Edit Transfer Request
     */
    public function editRequest($id)
    {
        $transfer = StockTransfer::with(['items.productVariant.product'])->findOrFail($id);
        
        if ($transfer->status != 'pending') {
            return redirect()->route('inventory-management.stock-transfer.transfer-request')
                ->with('error', 'Only pending transfers can be edited.');
        }

        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-transfer.transfer-request.edit', compact('transfer', 'stores'));
    }

    /**
     * Update Transfer Request
     */
  public function updateRequest(Request $request, $id)
{
    $transfer = StockTransfer::findOrFail($id);

    if ($transfer->status != 'pending') {
        return back()->with('error', 'Only pending transfers can be updated.');
    }

    // Validate all fields including from_store_id and to_store_id
    $validated = $request->validate([
        'from_store_id' => 'required|exists:stores,id',
        'to_store_id' => 'required|exists:stores,id|different:from_store_id',
        'transfer_date' => 'required|date',
        'expected_delivery_date' => 'nullable|date|after_or_equal:transfer_date',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.product_variant_id' => 'required|exists:product_variants,id',
        'items.*.requested_quantity' => 'required|integer|min:1',
        'items.*.notes' => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        $totalItems = count($validated['items']);
        $totalQuantity = array_sum(array_column($validated['items'], 'requested_quantity'));

        // Update transfer main details
        $transfer->update([
            'from_store_id' => $validated['from_store_id'],
            'to_store_id' => $validated['to_store_id'],
            'transfer_date' => $validated['transfer_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
        ]);

        // Delete old items
        $transfer->items()->delete();

        // Create updated items
        foreach ($validated['items'] as $index => $item) {
            StockTransferItem::create([
                'stock_transfer_id' => $transfer->id,
                'product_variant_id' => $item['product_variant_id'],
                'requested_quantity' => $item['requested_quantity'],
                'approved_quantity' => null,
                'transferred_quantity' => 0,
                'received_quantity' => 0,
                'damaged_quantity' => 0,
                'notes' => $item['notes'] ?? null,
                'sort_order' => $index,
            ]);
        }

        DB::commit();
        return redirect()->route('inventory-management.stock-transfer.transfer-request')
            ->with('success', 'Transfer request updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Error updating transfer request: ' . $e->getMessage());
    }
}

    /**
     * Cancel Transfer Request
     */
    public function cancelRequest($id)
    {
        $transfer = StockTransfer::findOrFail($id);

        if ($transfer->status != 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending transfers can be cancelled.']);
        }

        $transfer->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Transfer request cancelled successfully.']);
    }

    /**
     * Approval Listing
     */
    public function approval(Request $request)
    {
        if ($request->ajax()) {
            $query = StockTransfer::with(['fromStore', 'toStore', 'requestedBy'])
                ->whereIn('status', ['pending', 'approved']);

            // Filter by from store
            if ($request->has('from_store_id') && $request->from_store_id) {
                $query->where('from_store_id', $request->from_store_id);
            }

            return DataTables::of($query)
                ->addColumn('from_store_name', function($transfer) {
                    return $transfer->fromStore->name ?? 'N/A';
                })
                ->addColumn('to_store_name', function($transfer) {
                    return $transfer->toStore->name ?? 'N/A';
                })
                ->addColumn('requested_by_name', function($transfer) {
                    return $transfer->requestedBy->name ?? 'N/A';
                })
                ->addColumn('status_badge', function($transfer) {
                    $badges = [
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'approved' => '<span class="badge bg-success">Approved</span>',
                    ];
                    return $badges[$transfer->status] ?? '<span class="badge bg-secondary">Unknown</span>';
                })
                ->addColumn('action', function($transfer) {
                    $html = '<div class="btn-group">';
                    $html .= '<a href="' . route('inventory-management.stock-transfer.approval.view', $transfer->id) . '" class="btn btn-sm btn-info">View</a>';
                    if ($transfer->status == 'pending' && auth()->user()->can('approve_stock_transfer')) {
                        $html .= '<button onclick="approveTransfer(' . $transfer->id . ')" class="btn btn-sm btn-success">Approve</button>';
                        $html .= '<button onclick="rejectTransfer(' . $transfer->id . ')" class="btn btn-sm btn-danger">Reject</button>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-transfer.approval.index', compact('stores'));
    }

    /**
     * View Transfer for Approval
     */
    public function viewApproval($id)
    {
        $transfer = StockTransfer::with([
            'fromStore', 
            'toStore', 
            'requestedBy', 
            'approvedBy',
            'items.productVariant.product'
        ])->findOrFail($id);
        
        return view('inventory-management.stock-transfer.approval.view', compact('transfer'));
    }

    /**
     * Approve Transfer
     */
    public function approveTransfer(Request $request, $id)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.approved_quantity' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $transfer = StockTransfer::with('items')->findOrFail($id);

            if ($transfer->status != 'pending') {
                return response()->json(['success' => false, 'message' => 'Transfer is not pending approval.']);
            }

            // Update approved quantities and check stock availability
            foreach ($transfer->items as $item) {
                $approvedQty = $validated['items'][$item->id]['approved_quantity'] ?? 0;
                
                if ($approvedQty > 0) {
                    // Check stock availability
                    $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                        ->where('store_id', $transfer->from_store_id)
                        ->first();

                    $availableStock = $stock ? ($stock->quantity - $stock->reserved_quantity) : 0;

                    if ($approvedQty > $availableStock) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false, 
                            'message' => 'Insufficient stock for ' . ($item->productVariant->name ?? 'product') . '. Available: ' . $availableStock
                        ], 400);
                    }

                    $item->update(['approved_quantity' => $approvedQty]);
                }
            }

            // Update transfer status
            $transfer->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Update stock: move from available to in_transit
            foreach ($transfer->items as $item) {
                if ($item->approved_quantity > 0) {
                    $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                        ->where('store_id', $transfer->from_store_id)
                        ->first();

                    if ($stock) {
                        $stock->quantity -= $item->approved_quantity;
                        $stock->in_transit_quantity += $item->approved_quantity;
                        
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
                            'store_id' => $transfer->from_store_id,
                            'movement_type' => 'transfer_out',
                            'quantity' => -$item->approved_quantity,
                            'balance_after' => $stock->quantity,
                            'reference_type' => 'stock_transfer',
                            'reference_id' => $transfer->id,
                            'notes' => 'Stock transferred to ' . $transfer->toStore->name,
                            'created_by' => auth()->id(),
                            'movement_date' => $transfer->transfer_date,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transfer approved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error approving transfer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject Transfer
     */
    public function rejectTransfer(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $transfer = StockTransfer::findOrFail($id);

        if ($transfer->status != 'pending') {
            return response()->json(['success' => false, 'message' => 'Transfer is not pending approval.']);
        }

        $transfer->update([
            'status' => 'cancelled',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return response()->json(['success' => true, 'message' => 'Transfer rejected successfully.']);
    }

    /**
     * In-Transit Stock Listing
     */
    public function inTransit(Request $request)
    {
        if ($request->ajax()) {
            $query = StockTransfer::with(['fromStore', 'toStore', 'requestedBy'])
                ->where('status', 'approved');

            // Filter by from store
            if ($request->has('from_store_id') && $request->from_store_id) {
                $query->where('from_store_id', $request->from_store_id);
            }

            // Filter by to store
            if ($request->has('to_store_id') && $request->to_store_id) {
                $query->where('to_store_id', $request->to_store_id);
            }

            return DataTables::of($query)
                ->addColumn('from_store_name', function($transfer) {
                    return $transfer->fromStore->name ?? 'N/A';
                })
                ->addColumn('to_store_name', function($transfer) {
                    return $transfer->toStore->name ?? 'N/A';
                })
                ->addColumn('requested_by_name', function($transfer) {
                    return $transfer->requestedBy->name ?? 'N/A';
                })
                ->addColumn('action', function($transfer) {
                    $html = '<div class="btn-group">';
                    $html .= '<a href="' . route('inventory-management.stock-transfer.in-transit.view', $transfer->id) . '" class="btn btn-sm btn-info">View</a>';
                    $html .= '<button onclick="markInTransit(' . $transfer->id . ')" class="btn btn-sm btn-primary">Mark In Transit</button>';
                    $html .= '<button onclick="receiveTransfer(' . $transfer->id . ')" class="btn btn-sm btn-success">Receive</button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $stores = Store::where('status', 'active')->get();
        return view('inventory-management.stock-transfer.in-transit.index', compact('stores'));
    }

    /**
     * View In-Transit Transfer
     */
    public function viewInTransit($id)
    {
        $transfer = StockTransfer::with([
            'fromStore', 
            'toStore', 
            'requestedBy', 
            'approvedBy',
            'items.productVariant.product'
        ])->findOrFail($id);
        
        return view('inventory-management.stock-transfer.in-transit.view', compact('transfer'));
    }

    /**
     * Mark Transfer as In Transit
     */
    public function markInTransit($id)
    {
        $transfer = StockTransfer::findOrFail($id);

        if ($transfer->status != 'approved') {
            return response()->json(['success' => false, 'message' => 'Only approved transfers can be marked as in transit.']);
        }

        $transfer->update(['status' => 'in_transit']);

        return response()->json(['success' => true, 'message' => 'Transfer marked as in transit.']);
    }

    /**
     * Receive Transfer
     */
    public function receiveTransfer(Request $request, $id)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.received_quantity' => 'required|integer|min:0',
            'items.*.damaged_quantity' => 'nullable|integer|min:0',
            'received_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $transfer = StockTransfer::with('items')->findOrFail($id);

            if (!in_array($transfer->status, ['approved', 'in_transit'])) {
                return response()->json(['success' => false, 'message' => 'Transfer cannot be received in current status.']);
            }

            // Update received quantities
            foreach ($transfer->items as $item) {
                $receivedQty = $validated['items'][$item->id]['received_quantity'] ?? 0;
                $damagedQty = $validated['items'][$item->id]['damaged_quantity'] ?? 0;

                if ($receivedQty + $damagedQty > $item->approved_quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false, 
                        'message' => 'Received + Damaged quantity cannot exceed approved quantity for ' . ($item->productVariant->name ?? 'product')
                    ], 400);
                }

                $item->update([
                    'received_quantity' => $receivedQty,
                    'damaged_quantity' => $damagedQty,
                    'transferred_quantity' => $item->approved_quantity,
                ]);
            }

            // Update stock at destination store
            foreach ($transfer->items as $item) {
                if ($item->received_quantity > 0) {
                    $stock = ProductStock::where('product_variant_id', $item->product_variant_id)
                        ->where('store_id', $transfer->to_store_id)
                        ->first();

                    if (!$stock) {
                        $stock = ProductStock::create([
                            'product_variant_id' => $item->product_variant_id,
                            'store_id' => $transfer->to_store_id,
                            'quantity' => 0,
                            'reserved_quantity' => 0,
                            'in_transit_quantity' => 0,
                            'pending_quantity' => 0,
                            'min_stock_level' => 0,
                            'stock_status' => 'out_of_stock',
                        ]);
                    }

                    $stock->quantity += $item->received_quantity;
                    
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
                        'store_id' => $transfer->to_store_id,
                        'movement_type' => 'transfer_in',
                        'quantity' => $item->received_quantity,
                        'balance_after' => $stock->quantity,
                        'reference_type' => 'stock_transfer',
                        'reference_id' => $transfer->id,
                        'notes' => 'Stock received from ' . $transfer->fromStore->name,
                        'created_by' => auth()->id(),
                        'movement_date' => $validated['received_date'],
                    ]);
                }

                // Update in_transit quantity at source store
                $sourceStock = ProductStock::where('product_variant_id', $item->product_variant_id)
                    ->where('store_id', $transfer->from_store_id)
                    ->first();

                if ($sourceStock) {
                    $sourceStock->in_transit_quantity -= $item->approved_quantity;
                    $sourceStock->save();
                }
            }

            // Update transfer status
            $transfer->update([
                'status' => 'completed',
                'received_date' => $validated['received_date'],
                'received_by' => auth()->id(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transfer received successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error receiving transfer: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Search Product Variants for Transfer
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
                'current_stock' => $stock ? ($stock->quantity - $stock->reserved_quantity) : 0,
                'stock_status' => $stock ? $stock->stock_status : 'out_of_stock',
            ];
        });

        return response()->json(['variants' => $data]);
    }
}