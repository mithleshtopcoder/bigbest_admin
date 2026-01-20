@extends('layouts.app')

@section('title', 'Document Collection')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Document Collection</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Documents</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('onboarding.documents.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Upload Document
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Documents</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <select id="candidateFilter" class="form-select form-select-sm">
                        <option value="">All Candidates</option>
                        @foreach($candidates as $candidate)
                            <option value="{{ $candidate['id'] }}">{{ $candidate['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="submitted">Submitted</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="documentsTable" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Candidate name</th>
                            <th>Document Name</th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Verified By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const table = $('#documentsTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: {
                url: '{{ route("onboarding.documents.data") }}'
                , data: function(d) {
                    d.search = $('#searchInput').val();
                    d.candidate_id = $('#candidateFilter').val();
                    d.status = $('#statusFilter').val();
                    d.process_id = '{{ request("process_id") }}';
                }
            }
            , columns: [{
                    data: null
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }
                , {
                    data: 'candidate_name'
                }
                , {
                    data: 'document_name'
                }
                , {
                    data: 'document_type'
                }
                , {
                    data: 'status'
                    , orderable: false
                }
                , {
                    data: 'submitted_date'
                }
                , {
                    data: 'verified_by'
                }
                , {
                    data: null
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row) {
                        let actions = '<div class="d-flex align-items-center gap-1 justify-content-end">';
                        
                        // Add verify button if status is not verified
                        if (row.status_raw !== 'verified' && row.status_raw !== 'rejected') {
                            actions += '<button onclick="verifyDocument(' + row.id + ')" class="btn btn-sm btn-link text-success p-1" title="Verify">' +
                                '<i class="bi bi-check-circle"></i>' +
                                '</button>';
                        }
                        
                        actions += '<button onclick="deleteDocument(' + row.id + ')" class="btn btn-sm btn-link text-danger p-1" title="Delete">' +
                            '<i class="bi bi-trash"></i>' +
                            '</button>' +
                            '</div>';
                        return actions;
                    }
                }
            ]
            , order: [
                [0, 'asc']
            ]
            , pageLength: 25
        });

        $('#searchInput').on('keyup', function() {
            table.draw();
        });

        $('#candidateFilter').on('change', function() {
            table.draw();
        });

        $('#statusFilter').on('change', function() {
            table.draw();
        });
    });

    function verifyDocument(id) {
        if (confirm('Are you sure you want to verify this document?')) {
            $.ajax({
                url: '{{ route("onboarding.documents.update-status", ":id") }}'.replace(':id', id),
                type: 'POST',
                data: {
                    status: 'verified',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#documentsTable').DataTable().draw();
                        alert('Document verified successfully.');
                    }
                },
                error: function(xhr) {
                    alert('Error verifying document.');
                }
            });
        }
    }

    function deleteDocument(id) {
        if (confirm('Are you sure you want to delete this document?')) {
            $.ajax({
                url: '{{ route("onboarding.documents.destroy", ":id") }}'.replace(':id', id)
                , type: 'DELETE'
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , success: function(response) {
                    if (response.success) {
                        $('#documentsTable').DataTable().draw();
                        alert('Document deleted successfully.');
                    }
                }
            });
        }
    }

</script>
@endsection
