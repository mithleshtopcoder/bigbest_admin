<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorDocument;
use App\Helpers\MyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
   
   public function index()
{
    $vendors = User::with('vendorDocument') // eager load related vendor_documents
                   ->where('user_type', 'vendor')
                   ->where('is_access', 'user')
                   ->orderBy('id', 'desc')
                   ->paginate(10);

    return view('admin.vendors.index', compact('vendors'));
}


   
    public function create()
    {
        return view('admin.vendors.create');
    }

public function store(Request $request)
{
    // dd($request->all());

    $request->validate([
        'company_name'     => 'required|string|max:255', 
        'name'             => 'required|string|max:255',
        'email'            => 'required|email|unique:users,email',
        'phone'            => 'nullable|string|max:20',
        'password'         => 'required|string|min:6',
        'address'          => 'nullable|string|max:500',

        // Documents
        'pan_number'       => 'nullable|string|max:50',
        'aadhar_number'    => 'nullable|string|max:50',
        'gst_number'       => 'nullable|string|max:50',

        'pan_file'         => 'required|mimes:jpg,png,pdf|max:2048',
        'aadhar_file'      => 'required|mimes:jpg,png,pdf|max:2048',
        'gst_certificate'  => 'required|mimes:jpg,png,pdf|max:2048',

        // Bank
        'bank_name'        => 'nullable|string|max:255',
        'account_number'   => 'nullable|string|max:50',
        'account_type'     => 'nullable|string|max:50',
        'ifsc_code'        => 'nullable|string|max:50',
        'branch_name'      => 'nullable|string|max:255',
    ]);

    $vendorUid = 'VND-' . now()->format('YmdHis') . '-' . uniqid();

    DB::transaction(function () use ($request, $vendorUid) {

        /** -------------------------
         *  Upload documents
         * ------------------------- */
        $panFile = $request->hasFile('pan_file') 
            ? MyHelper::uploadFile($request->file('pan_file'), 'vendor-documents', 'pan-' . time()) 
            : null;

        $aadharFile = $request->hasFile('aadhar_file') 
            ? MyHelper::uploadFile($request->file('aadhar_file'), 'vendor-documents', 'aadhar-' . time()) 
            : null;

        $gstFile = $request->hasFile('gst_certificate') 
            ? MyHelper::uploadFile($request->file('gst_certificate'), 'vendor-documents', 'gst-' . time()) 
            : null;

        /** -------------------------
         *  Determine status based on logged-in user
         * ------------------------- */
        $status = auth()->user()->is_access === 'admin' ? 1 : 0;

        /** -------------------------
         *  Create User
         * ------------------------- */
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'mobile_number'  => $request->phone,
            'password'       => bcrypt($request->password),
            'user_type'      => 'vendor',
            'is_access'      => 'user',
            'vendor_id'      => $vendorUid,
            'status'         => $status, // set dynamically
        ]);

        /** -------------------------
         *  Create Vendor Documents
         * ------------------------- */
        VendorDocument::create([
            'vendor_id'       => $vendorUid,
            'company_name'    => $request->company_name,

            // Docs
            'pan_number'      => $request->pan_number,
            'aadhar_number'   => $request->aadhar_number,
            'gst_number'      => $request->gst_number,

            'pan_file'        => $panFile,
            'aadhar_file'     => $aadharFile,
            'gst_certificate' => $gstFile,

            // Address
            'address'         => $request->address,

            // Bank details
            'bank_name'       => $request->bank_name,
            'account_number'  => $request->account_number,
            'account_type'    => $request->account_type,
            'ifsc_code'       => $request->ifsc_code,
            'branch_name'     => $request->branch_name,
        ]);
    });

    return redirect()
        ->route('vendors.index')
        ->with('success', 'Vendor created successfully');
}


    public function show($id)
{
    $vendor = User::with('vendorDocument')->findOrFail($id);
    return view('admin.vendors.show', compact('vendor'));
}

    /**
 * Approve the vendor.
 */
public function approve($id)
{
    $user = User::findOrFail($id);

    // Update user status
    $user->status = 1; // approved
    $user->save();

    return redirect()->route('vendors.show', $user->id)
                     ->with('success', 'Vendor approved successfully.');
}

/**
 * Reject the vendor.
 */
public function reject($id)
{
    $user = User::findOrFail($id);

    // Update user status
    $user->status = 0; // rejected
    $user->save();

    return redirect()->route('vendors.show', $user->id)
                     ->with('success', 'Vendor rejected successfully.');
}


    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}