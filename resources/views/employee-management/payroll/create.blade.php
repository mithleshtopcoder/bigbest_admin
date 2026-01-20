@extends('layouts.app')

@section('title', 'Process Payroll')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Process Payroll</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.payroll') }}" class="text-decoration-none">Payroll</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Process</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.payroll') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Process Payroll for Employees</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-management.payroll.process') }}" method="POST" id="payrollForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Payroll Period <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="month" name="payroll_period" class="form-control" value="{{ old('payroll_period', date('Y-m')) }}" required>
                                <small class="text-muted">Select month for payroll (e.g., 2026-01)</small>
                                @error('payroll_period')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Period Start Date <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="period_start_date" class="form-control" id="periodStartDate" value="{{ old('period_start_date') }}" required>
                                @error('period_start_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Period End Date <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="period_end_date" class="form-control" id="periodEndDate" value="{{ old('period_end_date') }}" required>
                                @error('period_end_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Select Employees <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Select All</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Deselect All</button>
                                </div>
                                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px;">
                                    @foreach($employees as $emp)
                                        <div class="form-check">
                                            <input class="form-check-input employee-checkbox" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" id="emp_{{ $emp->id }}" {{ in_array($emp->id, old('employee_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="emp_{{ $emp->id }}">
                                                {{ $emp->user->name ?? 'N/A' }} ({{ $emp->employee_code ?? 'N/A' }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('employee_ids')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> Payroll will be calculated based on:
                            <ul class="mb-0 mt-2">
                                <li>Active salary structure for each employee</li>
                                <li>Attendance records for the selected period</li>
                                <li>Approved leaves</li>
                                <li>Holidays in the period</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calculator me-2"></i>Process Payroll
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-set period dates based on selected month
    $('input[name="payroll_period"]').on('change', function() {
        const period = $(this).val();
        if (period) {
            const [year, month] = period.split('-');
            const startDate = new Date(year, month - 1, 1);
            const endDate = new Date(year, month, 0);
            
            $('#periodStartDate').val(startDate.toISOString().split('T')[0]);
            $('#periodEndDate').val(endDate.toISOString().split('T')[0]);
        }
    });
    
    // Trigger on page load if period is set
    if ($('input[name="payroll_period"]').val()) {
        $('input[name="payroll_period"]').trigger('change');
    }
});

function selectAll() {
    $('.employee-checkbox').prop('checked', true);
}

function deselectAll() {
    $('.employee-checkbox').prop('checked', false);
}

$('#payrollForm').on('submit', function(e) {
    const checked = $('.employee-checkbox:checked').length;
    if (checked === 0) {
        e.preventDefault();
        alert('Please select at least one employee.');
        return false;
    }
});
</script>
@endsection

