<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    use HasFactory;

    protected $table = 'vendor_documents';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'vendor_id',
        'company_name',
        // Identity numbers
        'pan_number',
        'aadhar_number',
        'gst_number',

        // Document files
        'pan_file',
        'aadhar_file',
        'gst_certificate',

        // Address
        'address',

        // Bank details
        'bank_name',
        'account_number',
        'account_type',
        'ifsc_code',
        'branch_name',
    ];

    /**
     * Disable guarded
     */
    protected $guarded = [];

    /**
     * Relationship with User (Vendor)
     * vendor_documents.vendor_id → users.vendor_id
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'vendor_id');
    }

    /**
     * Accessor: PAN file URL
     */
    public function getPanFileUrlAttribute()
    {
        return $this->pan_file
            ? \MyHelper::getFile($this->pan_file, 'vendor-documents')
            : null;
    }

    /**
     * Accessor: Aadhaar file URL
     */
    public function getAadharFileUrlAttribute()
    {
        return $this->aadhar_file
            ? \MyHelper::getFile($this->aadhar_file, 'vendor-documents')
            : null;
    }

    /**
     * Accessor: GST file URL
     */
    public function getGstCertificateUrlAttribute()
    {
        return $this->gst_certificate
            ? \MyHelper::getFile($this->gst_certificate, 'vendor-documents')
            : null;
    }
}