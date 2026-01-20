@extends('layouts.app')

@section('title', 'Create Brand')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Create Brand</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('manage-product.brands.index') }}">Brands</a>
            </li>
            <li class="breadcrumb-item">Create</li>
        </ul>
    </div>
</div>
<!-- [ page-header ] end -->

<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('manage-product.brands.store') }}" method="POST" enctype="multipart/form-data" id="brandForm">
                        @csrf

                        <div class="row">
                            <div class="col-lg-8">

                                <div class="card-header py-2 mb-2 pl-1">
                                    <h5 class="card-title mb-0">Brand Information</h5>
                                </div>

                                {{-- Name --}}
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3">Brand Name <span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                {{-- Website --}}
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3">Website</label>
                                    <div class="col-md-9">
                                        <input type="url" name="website" class="form-control form-control-sm" value="{{ old('website') }}">
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="description" id="descriptionEditor" class="form-control form-control-sm" rows="4">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                {{-- Brand Image --}}
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3">Brand Image</label>
                                    <div class="col-md-9">
                                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*" onchange="previewImage(this, 'imagePreview')">
                                        <div class="mt-2">
                                            <img id="imagePreview" class="img-thumbnail d-none" style="width:120px;height:120px;object-fit:cover;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Brand Logo --}}
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3">Brand Logo</label>
                                    <div class="col-md-9">
                                        <input type="file" name="logo" class="form-control form-control-sm" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                                        <div class="mt-2">
                                            <img id="logoPreview" class="img-thumbnail d-none" style="width:120px;height:120px;object-fit:contain;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Sort Order --}}
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3">Sort Order</label>
                                    <div class="col-md-9">
                                        <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ old('sort_order', 0) }}">
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3">Status</label>
                                    <div class="col-md-9 d-flex align-items-center">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i> Save Brand
                                    </button>
                                    <a href="{{ route('manage-product.brands.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

<script>
    let descriptionEditor;

    document.addEventListener("DOMContentLoaded", function() {

        // CKEditor
        ClassicEditor
            .create(document.querySelector('#descriptionEditor'))
            .then(editor => descriptionEditor = editor)
            .catch(error => console.error(error));

        // Sync editor before submit
        document.getElementById('brandForm').addEventListener('submit', function() {
            document.querySelector('#descriptionEditor').value = descriptionEditor.getData();
        });
    });

    // Simple image preview
    function previewImage(input, previewId) {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

</script>
@endsection
