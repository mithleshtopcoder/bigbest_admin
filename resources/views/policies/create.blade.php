@extends('layouts.app')

@section('title', 'Create Policy')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Create Policy</h5>
</div>

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

<div class="card">
    <form action="{{ route('policies.store') }}" method="POST" id="policyForm">
        @csrf

        <div class="card-body">
            <div class="row">

                {{-- Policy Type --}}
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text w-35">Policy Type</span>
                        <select name="type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="terms_conditions">Terms & Conditions</option>
                            <option value="privacy_policy">Privacy Policy</option>
                            <option value="refund_policy">Refund Policy</option>
                            <option value="shipping_policy">Shipping Policy</option>
                        </select>
                    </div>
                </div>

                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text w-35">Title</span>
                        <input type="text" name="title" class="form-control" placeholder="Enter policy title" value="{{ old('title') }}">
                    </div>
                </div>

                {{-- Content --}}
                <div class="col-12 mb-3">
                    <div class="d-flex">
                        <div class="me-2" style="width:140px;">
                            <span class="input-group-text h-100">Content</span>
                        </div>

                        <div class="flex-fill border rounded">
                            <textarea name="content" id="editor" class="form-control border-0" rows="12" placeholder="Write policy content here...">{{ old('content') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="col-md-3 mb-3">
                    <div class="input-group">
                        <span class="input-group-text w-35">Sort Order</span>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-md-3 mb-3 d-flex align-items-center">
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                        <label class="form-check-label ms-2" for="is_active">Active</label>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="card-footer d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">
                <i class="feather-save me-1"></i> Save Policy
            </button>

            <a href="{{ route('policies.index') }}" class="btn btn-light">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

<script>
    let policyEditor;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                shouldNotGroupWhenFull: true
            }
        })
        .then(editor => {
            policyEditor = editor;

            // ✅ Increase editor height
            editor.ui.view.editable.element.style.minHeight = '100px';
        })
        .catch(error => {
            console.error(error);
        });

    // Sync editor before submit
    document.getElementById('policyForm').addEventListener('submit', function() {
        document.querySelector('#editor').value = policyEditor.getData();
    });

</script>
@endsection
