@extends('layouts.app')

@section('title', 'Edit Banner')

@section('styles')
<style>
    .field-group {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
        height: 44px;
    }

    .field-label {
        background: #f5f6f8;
        padding: 10px 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        border-right: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .field-input input,
    .field-input select {
        border: 0;
        border-radius: 0;
        height: 44px;
        box-shadow: none;
    }

</style>
@endsection

@section('content')
<div class="page-header">
    <h5>Edit Banner</h5>
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
    <div class="card-body">
        <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ✅ checkbox fallback (VERY IMPORTANT) --}}
            <input type="hidden" name="is_active" value="0">

            <div class="row">

                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <div class="field-group">
                        <div class="row g-0 h-100">
                            <div class="col-4 field-label">
                                Title <span class="text-danger ms-1">*</span>
                            </div>
                            <div class="col-8 field-input">
                                <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Redirect Link --}}
                <div class="col-md-6 mb-3">
                    <div class="field-group">
                        <div class="row g-0 h-100">
                            <div class="col-4 field-label">
                                Redirect Link
                            </div>
                            <div class="col-8 field-input">
                                <input type="url" name="link" class="form-control" value="{{ old('link', $banner->link) }}" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Type --}}
                <div class="col-md-6 mb-3">
                    <div class="field-group">
                        <div class="row g-0 h-100">
                            <div class="col-4 field-label">
                                Type
                            </div>
                            <div class="col-8 field-input">
                                <select name="type" class="form-control">
                                    @foreach(['home','category','product','promotional','other'] as $type)
                                    <option value="{{ $type }}" {{ old('type', $banner->type) === $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Position --}}
                <div class="col-md-6 mb-3">
                    <div class="field-group">
                        <div class="row g-0 h-100">
                            <div class="col-4 field-label">
                                Position
                            </div>
                            <div class="col-8 field-input">
                                <input type="text" name="position" class="form-control" value="{{ old('position', $banner->position) }}" placeholder="Top / Middle / Bottom">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="col-md-6 mb-3">
                    <div class="field-group">
                        <div class="row g-0 h-100">
                            <div class="col-4 field-label">
                                Sort Order
                            </div>
                            <div class="col-8 field-input">
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Active --}}
                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $banner->is_active) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold">
                            Active
                        </label>
                    </div>
                </div>

                {{-- Banner Image --}}
                <div class="col-md-6 mb-3">
                    <div class="field-group">
                        <div class="row g-0 h-100">
                            <div class="col-4 field-label">
                                Banner Image
                            </div>
                            <div class="col-8 field-input">
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                    </div>

                    @if($banner->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/'.$banner->image) }}" class="border rounded" style="width:160px;height:90px;object-fit:cover;">
                    </div>
                    @endif
                </div>

            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-between border-top pt-3 mt-4">
                <button class="btn btn-primary">
                    <i class="feather-save"></i> Update Banner
                </button>

                <a href="{{ route('banners.index') }}" class="btn btn-light">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
