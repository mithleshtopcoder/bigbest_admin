@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Upload Document</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.documents') }}" class="text-decoration-none">Documents</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Upload</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Document Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('onboarding.documents.store') }}" method="POST" enctype="multipart/form-data" id="documentsForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Onboarding Process <span class="text-danger">*</span></label>
                            <div class="col-md-7 ps-1">
                                <select class="form-select form-control" name="onboarding_process_id" id="onboarding_process_id" required>
                                    <option value="">Select Process</option>
                                    @foreach($processes as $proc)
                                        <option value="{{ $proc->id }}" {{ (request('process_id') == $proc->id || old('onboarding_process_id') == $proc->id) ? 'selected' : '' }}>
                                            {{ $proc->user->name ?? $proc->candidate->full_name ?? 'N/A' }} - {{ $proc->joining_date }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="documentsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll" title="Select All">
                                </th>
                                <th>Document Type <span class="text-danger">*</span></th>
                                <th>Document Name <span class="text-danger">*</span></th>
                                <th>File <span class="text-danger">*</span></th>
                                <th>Status</th>
                                <th width="80">Action</th>
                            </tr>
                        </thead>
                        <tbody id="documentsTableBody">
                            <tr class="document-row">
                                <td>
                                    <input type="checkbox" class="document-checkbox" name="documents[0][include]" checked>
                                </td>
                                <td>
                                    <select class="form-select form-control form-control-sm" name="documents[0][document_type]" required>
                                        <option value="">Select</option>
                                        <option value="aadhar">Aadhar</option>
                                        <option value="pan">PAN</option>
                                        <option value="passport">Passport</option>
                                        <option value="educational_certificates">Educational Certificates</option>
                                        <option value="experience_letters">Experience Letters</option>
                                        <option value="other">Other</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="documents[0][document_name]" placeholder="Document Name" required>
                                </td>
                                <td>
                                    <input type="file" class="form-control form-control-sm" name="documents[0][file_path]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                </td>
                                <td>
                                    <select class="form-select form-control form-control-sm" name="documents[0][status]">
                                        <option value="submitted">Submitted</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeDocumentRow(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addDocumentRow()">
                        <i class="bi bi-plus-circle me-2"></i>Add Row
                    </button>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Upload Selected</button>
                        <a href="{{ route('onboarding.documents') }}" class="btn btn-light">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let documentRowIndex = 1;

function addDocumentRow() {
    const tbody = document.getElementById('documentsTableBody');
    const newRow = document.createElement('tr');
    newRow.className = 'document-row';
    newRow.innerHTML = `
        <td>
            <input type="checkbox" class="document-checkbox" name="documents[${documentRowIndex}][include]" checked>
        </td>
        <td>
            <select class="form-select form-control form-control-sm" name="documents[${documentRowIndex}][document_type]" required>
                <option value="">Select</option>
                <option value="aadhar">Aadhar</option>
                <option value="pan">PAN</option>
                <option value="passport">Passport</option>
                <option value="educational_certificates">Educational Certificates</option>
                <option value="experience_letters">Experience Letters</option>
                <option value="other">Other</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="documents[${documentRowIndex}][document_name]" placeholder="Document Name" required>
        </td>
        <td>
            <input type="file" class="form-control form-control-sm" name="documents[${documentRowIndex}][file_path]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
        </td>
        <td>
            <select class="form-select form-control form-control-sm" name="documents[${documentRowIndex}][status]">
                <option value="submitted">Submitted</option>
                <option value="pending">Pending</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeDocumentRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
    documentRowIndex++;
}

function removeDocumentRow(button) {
    const row = button.closest('tr');
    if (document.querySelectorAll('.document-row').length > 1) {
        row.remove();
    } else {
        alert('At least one document row is required.');
    }
}

// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.document-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Form submission - validate checked rows
document.getElementById('documentsForm').addEventListener('submit', function(e) {
    const checkedRows = document.querySelectorAll('.document-checkbox:checked');
    if (checkedRows.length === 0) {
        e.preventDefault();
        alert('Please select at least one document to upload.');
        return false;
    }
    
    // Validate that all checked rows have required fields
    let isValid = true;
    checkedRows.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const documentType = row.querySelector('[name*="[document_type]"]').value;
        const documentName = row.querySelector('[name*="[document_name]"]').value;
        const fileInput = row.querySelector('[name*="[file_path]"]');
        
        if (!documentType || !documentName || !fileInput.files.length) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill all required fields for selected documents.');
        return false;
    }
});
</script>
@endsection
