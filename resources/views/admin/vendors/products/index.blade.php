@extends('layouts.app') {{-- Your vendor layout --}}
@section('title', 'My Products')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <h1 class="page-title">My Products</h1>
    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Product
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped" id="productsTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Variants</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('#productsTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: '{{ route("vendor.products.index") }}'
            , columns: [{
                    data: 'product'
                    , name: 'product'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'category'
                    , name: 'category'
                }
                , {
                    data: 'variants'
                    , name: 'variants'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'stock'
                    , name: 'stock'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'status'
                    , name: 'status'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'actions'
                    , name: 'actions'
                    , orderable: false
                    , searchable: false
                }
            , ]
            , order: [
                [1, 'asc']
            ]
            , pageLength: 10
            , responsive: true
        , });
    });

</script>
@endsection
