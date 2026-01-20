@extends('layouts.app')

@section('title', 'Banners')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Banners</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">CMS</li>
            <li class="breadcrumb-item">Banners</li>
        </ul>
    </div>

    <div class="page-header-right ms-auto">
        <div class="page-header-right-items-wrapper">
            {{-- ✅ ADD BANNER --}}
            <a href="{{ route('banners.create') }}" class="btn btn-primary btn-sm">
                <i class="feather-plus me-2"></i>Add Banner
            </a>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->

<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">

                <div class="card-header">
                    <h5 class="card-title">Banners List</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Position</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($banners as $banner)
                                <tr>
                                    {{-- Image --}}
                                    <td class="align-middle">
                                        <div class="banner-thumb">
                                            <img src="{{ $banner->image
                ? asset('storage/'.$banner->image)
                : asset('assets/images/placeholder-banner.jpg') }}" alt="Banner">
                                        </div>
                                    </td>


                                    {{-- Title --}}
                                    <td>{{ $banner->title }}</td>

                                    {{-- Type --}}
                                    <td>
                                        <span class="badge bg-soft-primary text-primary text-capitalize">
                                            {{ $banner->type }}
                                        </span>
                                    </td>

                                    {{-- Position --}}
                                    <td>{{ $banner->position ?? '-' }}</td>

                                    {{-- Sort --}}
                                    <td>{{ $banner->sort_order }}</td>

                                    {{-- Status --}}
                                    <td>
                                        @if($banner->is_active)
                                        <span class="badge bg-soft-success text-success">Active</span>
                                        @else
                                        <span class="badge bg-soft-danger text-danger">Inactive</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">

                                            {{-- ✏️ Edit --}}
                                            <a href="{{ route('banners.edit', $banner->id) }}" class="avatar-text avatar-sm" title="Edit">
                                                <i class="feather-edit text-primary"></i>
                                            </a>

                                            {{-- 🗑 Delete --}}
                                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="avatar-text avatar-sm border-0 bg-transparent">
                                                    <i class="feather-trash-2 text-danger"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No banners found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('styles')
<style>
    .banner-thumb {
        width: 90px;
        height: 50px;
        overflow: hidden;
        border-radius: 6px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    table td {
        vertical-align: middle !important;
    }

</style>
@endsection
