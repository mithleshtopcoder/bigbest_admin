<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\MyHelper;
use Illuminate\Support\Facades\DB;
class VendorController extends Controller
{
   
    public function index()
    {
        $vendors = Vendor::latest()->paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }

   
    public function create()
    {
        return view('admin.vendors.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'name'               => 'required|string|max:255',
        'email'              => 'required|email|unique:users,email',
        'phone'              => 'nullable|string|max:20',
        'store_name'         => 'nullable|string|max:255',
        'password'           => 'required|string|min:6|confirmed',
        'pan_number'         => 'nullable|string|max:50',
        'gst_number'         => 'nullable|string|max:50',
        'address'            => 'nullable|string|max:500',
        'pan_file'           => 'required|mimes:jpg,png,pdf|max:2048',
        'gst_file'           => 'nullable|mimes:jpg,png,pdf|max:2048',
        'address_proof_file' => 'nullable|mimes:jpg,png,pdf|max:2048',
    ]);

    $status = $request->routeIs('vendors.store') ? 'approved' : 'pending';

    $vendorUid = 'VND-' . now()->format('YmdHis') . '-' . uniqid();

    DB::transaction(function () use ($request, $status, $vendorUid) {


        // 2️⃣ Upload KYC files
        $kycFiles = [
            'pan_file'           => 'pan',
            'gst_file'           => 'gst',
            'address_proof_file' => 'address_proof'
        ];

        foreach ($kycFiles as $input => $prefix) {
            if ($request->hasFile($input)) {
                $vendor->$input = MyHelper::uploadFile(
                    $request->file($input),
                    'vendors/kyc',
                    $prefix . '-' . $vendor->id
                );
            }
        }

        $vendor->save();

        // 3️⃣ Create linked user (THIS IS THE KEY CHANGE)
        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'mobile_number' => $request->phone,
            'password'      => bcrypt($request->password),
            'status'        => $status === 'approved' ? 1 : 0,
            'user_type'     => 'vendor',
            'vendor_id'     => $vendorUid, // ✅ LINKED HERE
        ]);
    });

    return redirect()->route('vendors.index')
        ->with('success', "Vendor created successfully. Status: $status");
}


    public function show(Vendor $vendor)
    {
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
 * Approve the vendor.
 */
public function approve(Vendor $vendor)
{
    $vendor->status = 'approved';
    $vendor->save();

    // Update linked user status
    $user = $vendor->user; // assuming you have a relationship defined
    if ($user) {
        $user->status = 1;
        $user->save();
    }

    return redirect()->route('vendors.show', $vendor->id)
                     ->with('success', 'Vendor approved successfully.');
}

/**
 * Reject the vendor.
 */
public function reject(Vendor $vendor)
{
    $vendor->status = 'rejected';
    $vendor->save();

    // Update linked user status
    $user = $vendor->user;
    if ($user) {
        $user->status = 0;
        $user->save();
    }

    return redirect()->route('vendors.show', $vendor->id)
                     ->with('success', 'Vendor rejected successfully.');
}


    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}