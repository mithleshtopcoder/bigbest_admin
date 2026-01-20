<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 dummy vendors
        for ($i = 1; $i <= 10; $i++) {

            // Generate unique vendor UID
            $vendorUid = 'VND-' . date('YmdHis') . '-' . Str::random(6);

            Vendor::create([
                'vendor_uid'           => $vendorUid,
                'name'                 => 'Vendor ' . $i,
                'email'                => 'vendor' . $i . '@example.com',
                'phone'                => '98765432' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'password'             => bcrypt('password123'),
                'store_name'           => 'Store ' . $i,
                'address'              => '123 Vendor Street, City ' . $i,
                'pan_number'           => 'ABCDE1234F',
                'gst_number'           => '22ABCDE1234F1Z5',
                'pan_file'             => null, // you can add dummy files if needed
                'gst_file'             => null,
                'address_proof_file'   => null,
                'status'               => 'approved',
            ]);
        }
    }
}