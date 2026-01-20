@extends('layouts.app')

@section('title', 'Policies')

@section('content')

<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">New Policy</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">New Policy</li>
            {{-- <li class="breadcrumb-item">POS Orders</li> --}}
        </ul>
    </div>
    <div class="page-header-right ms-auto">
        <div class="page-header-right-items">
            <div class="d-flex d-md-none">
                <a href="javascript:void(0)" class="page-header-right-close-toggle py-2">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back</span>
                </a>
            </div>
            <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                <a href="{{ route('policies.create') }}" class="btn btn-md btn-primary btn-xs">
                    <i class="feather-plus me-2"></i>Create Policy
                </a>
                <div class="dropdown filter-dropdown">
                    <a class="btn btn-md btn-light-brand" style="padding: 5px 8px;" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                        <i class="feather-filter me-2"></i>
                        <span>Filter</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="StoreFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="StoreFilter">Store</label>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="DateFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="DateFilter">Date</label>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="PaymentFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="PaymentFilter">Payment Method</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-md-none d-flex align-items-center">
            <a href="javascript:void(0)" class="page-header-right-open-toggle">
                <i class="feather-align-right fs-20"></i>
            </a>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">#</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th width="100">Sort Order</th>
                    <th width="120" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($policies as $policy)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <span class="badge bg-info">
                            {{ str_replace('_',' ', ucfirst($policy->type)) }}
                        </span>
                    </td>

                    <td>{{ $policy->title }}</td>

                    <td>
                        <span class="badge {{ $policy->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $policy->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td>{{ $policy->sort_order }}</td>

                    <td class="text-center">
                        <div class="d-inline-flex gap-1">

                            <a href="{{ route('policies.edit', $policy->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="feather-edit"></i>
                            </a>

                            <form action="{{ route('policies.destroy', $policy->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Delete">
                                    <i class="feather-trash-2"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No policies found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
