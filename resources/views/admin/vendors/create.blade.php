@extends('layouts.app')

@section('title', 'Add Vendor')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Add New Vendor</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="status" value="approved">

            <div class="row g-3">

                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Store Name -->
                <div class="col-md-6">
                    <label class="form-label">Store Name</label>
                    <input type="text" name="store_name" class="form-control @error('store_name') is-invalid @enderror" value="{{ old('store_name') }}">
                    @error('store_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- PAN Number -->
                <div class="col-md-6">
                    <label class="form-label">PAN Number</label>
                    <input type="text" name="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}">
                    @error('pan_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- GST Number -->
                <div class="col-md-6">
                    <label class="form-label">GST Number</label>
                    <input type="text" name="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number') }}">
                    @error('gst_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- KYC Documents Section -->
                <h5 class="mt-4">KYC Documents</h5>

                <!-- PAN Card -->
                <div class="col-md-6">
                    <label class="form-label">PAN Card <span class="text-danger">*</span></label>
                    <input type="file" name="pan_file" id="pan_file" class="form-control @error('pan_file') is-invalid @enderror" accept=".jpg,.png,.pdf" required onchange="previewFile(this, 'pan_preview_container')">
                    @error('pan_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="pan_preview_container" class="mt-2"></div>
                </div>

                <!-- GST Document -->
                <div class="col-md-6">
                    <label class="form-label">GST Document</label>
                    <input type="file" name="gst_file" id="gst_file" class="form-control @error('gst_file') is-invalid @enderror" accept=".jpg,.png,.pdf" onchange="previewFile(this, 'gst_preview_container')">
                    @error('gst_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="gst_preview_container" class="mt-2"></div>
                </div>

                <!-- Address Proof -->
                <div class="col-md-6">
                    <label class="form-label">Address Proof</label>
                    <input type="file" name="address_proof_file" id="address_proof_file" class="form-control @error('address_proof_file') is-invalid @enderror" accept=".jpg,.png,.pdf" onchange="previewFile(this, 'address_preview_container')">
                    @error('address_proof_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="address_preview_container" class="mt-2"></div>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Vendor</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
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
