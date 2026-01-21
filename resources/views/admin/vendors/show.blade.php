@extends('layouts.app')

@section('title', 'Vendor Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Vendor Details - {{ $vendor->name }}</h4>
        <a href="{{ route('vendors.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
    </div>

    <div class="card-body">
        {{-- Basic Info --}}
        <h5 class="mb-3">Basic Information</h5>
        <table class="table table-bordered">
            <tr>
                <th>Company Name</th>
                <td>{{ $vendor->vendorDocument->company_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $vendor->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $vendor->email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $vendor->mobile_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $vendor->vendorDocument->address ?? '-' }}</td>
            </tr>
            <tr>
                <th>PAN Number</th>
                <td>{{ $vendor->vendorDocument->pan_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Aadhar Number</th>
                <td>{{ $vendor->vendorDocument->aadhar_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>GST Number</th>
                <td>{{ $vendor->vendorDocument->gst_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($vendor->status == 'approved' || $vendor->status == 1)
                    <span class="badge bg-success">Approved</span>
                    @else
                    <span class="badge bg-warning">Pending</span>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Bank Details --}}
        <h5 class="mt-4 mb-3">Bank Details</h5>
        <table class="table table-bordered">
            <tr>
                <th>Bank Name</th>
                <td>{{ $vendor->vendorDocument->bank_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Account Number</th>
                <td>{{ $vendor->vendorDocument->account_number ?? '-' }}</td>
            </tr>
            <tr>
                <th>Account Type</th>
                <td>{{ $vendor->vendorDocument->account_type ?? '-' }}</td>
            </tr>
            <tr>
                <th>IFSC Code</th>
                <td>{{ $vendor->vendorDocument->ifsc_code ?? '-' }}</td>
            </tr>
            <tr>
                <th>Branch Name</th>
                <td>{{ $vendor->vendorDocument->branch_name ?? '-' }}</td>
            </tr>
        </table>

        {{-- KYC Documents --}}
        <h5 class="mt-4 mb-3">KYC Documents</h5>
        <div class="row">
            @php
            $kycDocs = [
            'PAN Card' => $vendor->vendorDocument->pan_file ?? null,
            'Aadhar Card' => $vendor->vendorDocument->aadhar_file ?? null,
            'GST Certificate' => $vendor->vendorDocument->gst_certificate ?? null
            ];
            @endphp

            @foreach($kycDocs as $label => $file)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">{{ $label }}</div>
                    <div class="card-body text-center">
                        @if($file)
                        @php
                        $fileUrl = asset('documents/vendor-documents/' . urlencode($file));
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        @endphp

                        @if(in_array($ext, ['jpg','jpeg','png','gif']))
                        <img src="{{ $fileUrl }}" class="img-fluid mb-2" style="max-height:150px; cursor:pointer;" onclick="window.open('{{ $fileUrl }}', '_blank')">
                        @elseif($ext === 'pdf')
                        <i class="bi bi-file-earmark-pdf" style="font-size:48px; cursor:pointer;" onclick="window.open('{{ $fileUrl }}', '_blank')"></i>
                        <div class="mt-2">
                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-primary">View PDF</a>
                        </div>
                        @else
                        <span class="text-muted">File type not supported</span>
                        @endif
                        @else
                        <span class="text-muted">No file uploaded</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="mt-4">
            @if($vendor->status != 'approved')
            <form action="{{ route('vendors.approve', $vendor->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-success">Approve</button>
            </form>
            <form action="{{ route('vendors.reject', $vendor->id) }}" method="POST" class="d-inline ms-2">
                @csrf
                <button class="btn btn-danger">Reject</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
