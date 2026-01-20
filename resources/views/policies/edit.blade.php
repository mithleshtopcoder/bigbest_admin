@extends('layouts.app')

@section('title', 'Edit Policy')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Edit Policy</h5>
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
    <form action="{{ route('policies.update', $policy->id) }}" method="POST" id="policyForm">
        @csrf
        @method('PUT')

        {{-- Checkbox fix --}}
        <input type="hidden" name="is_active" value="0">

        <div class="card-body">
            <div class="row">

                {{-- Policy Type --}}
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text w-35">Policy Type</span>
                        <select name="type" class="form-control" required>
                            @foreach([
                            'terms_conditions' => 'Terms & Conditions',
                            'privacy_policy' => 'Privacy Policy',
                            'refund_policy' => 'Refund Policy',
                            'shipping_policy' => 'Shipping Policy'
                            ] as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $policy->type) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text w-35">Title</span>
                        <input type="text" name="title" class="form-control" placeholder="Enter policy title" value="{{ old('title', $policy->title) }}">
                    </div>
                </div>

                {{-- Content --}}

                <div class="col-12 mb-3">
                    <div class="d-flex">
                        <div class="me-2" style="width:140px;">
                            <span class="input-group-text h-100">Content</span>
                        </div>

                        <div class="flex-fill border rounded">
                            <textarea name="content" id="editor" class="form-control">
                            {{ old('content', $policy->content) }}
                            </textarea> </div>
                    </div>
                </div>



                {{-- Sort Order --}}
                <div class="col-md-3 mb-3">
                    <div class="input-group">
                        <span class="input-group-text w-35">Sort Order</span>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $policy->sort_order) }}">
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-md-3 mb-3 d-flex align-items-center">
                    <div class="form-check form-switch mt-4">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ old('is_active', $policy->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="card-footer d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary">
                <i class="feather-save me-1"></i> Update Policy
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

            // ✅ Compact editor height
            editor.ui.view.editable.element.style.minHeight = '180px';
        })
        .catch(error => console.error(error));

    document.getElementById('policyForm').addEventListener('submit', function() {
        document.querySelector('#editor').value = policyEditor.getData();
    });

</script>
@endsection
