<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers/vendors
     */
    public function index($type)
    {
        return view('party-management.suppliers-vendors.index', ['type' => $type]);
    }

    /**
     * AJAX endpoint for fetching suppliers/vendors with pagination (DataTables)
     */
    public function getSuppliers(Request $request, $type)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        // Build query
        $query = Supplier::query();

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('contact_person', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('gst_number', 'like', '%' . $search . '%')
                  ->orWhere('pan_number', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Get total count before pagination
        $totalRecords = Supplier::count();
        $filteredRecords = $query->count();

        // Apply pagination and ordering
        $suppliers = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $suppliers->map(function ($supplier) {
            $statusBadge = match($supplier->status) {
                'active' => '<span class="badge bg-success">Active</span>',
                'inactive' => '<span class="badge bg-danger">Inactive</span>',
                'suspended' => '<span class="badge bg-warning">Suspended</span>',
                default => '<span class="badge bg-secondary">' . ucfirst($supplier->status) . '</span>'
            };

            $address = collect([
                $supplier->address,
                $supplier->city,
                $supplier->state,
                $supplier->pincode
            ])->filter()->implode(', ');

            return [
                'id' => $supplier->id,
                'code' => $supplier->code ?? 'N/A',
                'name' => $supplier->name ?? 'N/A',
                'contact_person' => $supplier->contact_person ?? 'N/A',
                'email' => $supplier->email ?? 'N/A',
                'phone' => $supplier->phone ?? 'N/A',
                'address' => $address ? (strlen($address) > 50 ? substr($address, 0, 50) . '...' : $address) : 'N/A',
                'gst_number' => $supplier->gst_number ?? 'N/A',
                'status' => $statusBadge,
                'status_value' => $supplier->status,
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
     * Show the form for creating a new supplier/vendor
     */
    public function create($type)
    {
        return view('party-management.suppliers-vendors.create', ['type' => $type]);
    }

    /**
     * Store a newly created supplier/vendor
     */
    public function store(Request $request, $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:suppliers,code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'nullable|string|max:10',
            'payment_terms' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            DB::beginTransaction();

            // Set default country if not provided
            if (empty($validated['country'])) {
                $validated['country'] = 'India';
            }

            // Auto-generate code if not provided
            if (empty($validated['code'])) {
                $lastSupplier = Supplier::orderBy('id', 'desc')->first();
                $nextNumber = $lastSupplier ? $lastSupplier->id + 1 : 1;
                $validated['code'] = strtoupper($type) . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            Supplier::create($validated);

            DB::commit();

            return redirect()
                ->route('party-management.suppliers-vendors.index', ['type' => $type])
                ->with('success', ucfirst($type) . ' created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to create ' . $type . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified supplier/vendor
     */
    public function show($type, $id)
    {
        $supplier = Supplier::with([
            'purchaseOrders' => function($q) {
                $q->orderBy('created_at', 'desc')->limit(10);
            },
            'purchaseReceipts' => function($q) {
                $q->orderBy('created_at', 'desc')->limit(10);
            }
        ])->findOrFail($id);

        return view('party-management.suppliers-vendors.show', [
            'supplier' => $supplier,
            'type' => $type
        ]);
    }
    
    /**
     * Show the form for editing the specified supplier/vendor
     */
    public function edit($type, $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('party-management.suppliers-vendors.edit', [
            'supplier' => $supplier,
            'type' => $type
        ]);
    }

    /**
     * Update the specified supplier/vendor
     */
    public function update(Request $request, $type, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:suppliers,code,' . $id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'nullable|string|max:10',
            'payment_terms' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        try {
            DB::beginTransaction();

            // Set default country if not provided
            if (empty($validated['country'])) {
                $validated['country'] = 'India';
            }

            $supplier->update($validated);

            DB::commit();

            return redirect()
                ->route('party-management.suppliers-vendors.index', ['type' => $type])
                ->with('success', ucfirst($type) . ' updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to update ' . $type . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified supplier/vendor
     */
    public function destroy($type, $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Check if supplier has any related records
            $hasPurchaseOrders = $supplier->purchaseOrders()->count() > 0;
            $hasPurchaseReceipts = $supplier->purchaseReceipts()->count() > 0;
            $hasPurchaseInvoices = $supplier->purchaseInvoices()->count() > 0;
            $hasPurchaseReturns = $supplier->purchaseReturns()->count() > 0;

            if ($hasPurchaseOrders || $hasPurchaseReceipts || $hasPurchaseInvoices || $hasPurchaseReturns) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete ' . $type . ' because it has associated purchase orders, receipts, invoices, or returns. Please deactivate it instead.'
                ], 422);
            }

            $supplier->delete();

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ' . $type . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
