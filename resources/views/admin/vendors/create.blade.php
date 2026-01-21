@extends('layouts.app')
@section('title', 'Add Vendor')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Add Vendor</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}" class="text-decoration-none">Vendor Management</a></li>
                    <li class="breadcrumb-item">Manage Vendor</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Vendor</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Add New Vendor</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row ps-2">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Company Name <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label class="form-label col-md-2">Name <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="form-label col-md-2">Phone</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label class="form-label col-md-2">Email <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-label col-md-2" style="height: 80px;">Address</label>
                            <div class="col-md-10 ps-1">
                                <textarea name="address" style="height: 80px;" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="form-label col-md-2">PAN Number</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}">
                                @error('pan_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label class="form-label col-md-2">PAN Card <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="file" name="pan_file" class="form-control @error('pan_file') is-invalid @enderror" value="{{ old('pan_file') }}" required>
                                @error('aadhar_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Aadhar Number</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}">
                                @error('pan_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label class="form-label col-md-2">Aadhar Card  <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="file" name="aadhar_number" class="form-control @error('aadhar_number') is-invalid @enderror" value="{{ old('aadhar_number') }}" required>
                                @error('aadhar_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-label col-md-2">GST Number</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}">
                                @error('pan_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label class="form-label col-md-2">GST Certificate <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="file" name="gst_certificate" class="form-control @error('gst_certificate') is-invalid @enderror" value="{{ old('gst_certificate') }}" required>
                                @error('gst_certificate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="form-label col-md-2">Password</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="address_proof_file" class="form-control @error('address_proof_file') is-invalid @enderror" value="{{ old('address_proof_file') }}">
                                @error('address_proof_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label class="form-label col-md-2">Confirm Password <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="gst_certificate" class="form-control @error('gst_certificate') is-invalid @enderror" value="{{ old('gst_certificate') }}" required>
                                @error('gst_certificate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <h5 class="mt-4">Bank Details</h5>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Bank Name</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
                                @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Account Number</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}">
                                @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Account Type</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="account_type" class="form-control @error('account_type') is-invalid @enderror" value="{{ old('account_type') }}">
                                @error('account_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">IFSC Code</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" value="{{ old('ifsc_code') }}">
                                @error('ifsc_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Branch Name</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name') }}">
                                @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center my-3">
                        <button class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i> Save Vendor</button>
                        <a href="{{ route('vendors.index') }}" class="btn btn-secondary btn-sm ms-2">Cancel</a>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Toggle password visibility
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    // Preview uploaded file (image or PDF)
    function previewFile(input, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = ''; // clear previous preview

        const file = input.files[0];
        if (!file) return;

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.classList.add('img-fluid');
            img.style.maxHeight = '120px';
            img.style.cursor = 'pointer';
            img.onclick = () => viewFullScreen(img);
            container.appendChild(img);
        } else if (file.type === 'application/pdf') {
            const a = document.createElement('a');
            a.href = URL.createObjectURL(file);
            a.target = '_blank';
            a.className = 'btn btn-outline-secondary';
            a.innerHTML = '<i class="bi bi-file-earmark-pdf me-1"></i> View PDF';
            container.appendChild(a);
        }
    }

    // View image full screen
    function viewFullScreen(img) {
        const src = img.src;
        const modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = 0;
        modal.style.left = 0;
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0,0,0,0.8)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = 9999;
        modal.innerHTML = `<img src="${src}" style="max-width:90%; max-height:90%; border-radius:5px;">
                           <span style="position:absolute;top:20px;right:30px;font-size:30px;color:#fff;cursor:pointer;">&times;</span>`;
        document.body.appendChild(modal);

        modal.querySelector('span').addEventListener('click', () => modal.remove());
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.remove();
        });
    }

</script>
@endsection
