@extends('layouts.app')

@section('title', 'Create Salary Structure - ' . $employee->user->name)

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Salary Structure</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.salary-structure.list') }}" class="text-decoration-none">Salary Structure</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.salary-structure.index', $employee->user->uuid) }}" class="text-decoration-none">{{ $employee->user->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.salary-structure.index', $employee->user->uuid) }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Salary Structure for {{ $employee->user->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-management.salary-structure.store', $employee->user->uuid) }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Allowances</h6>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Basic Salary <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="number" name="basic_salary" class="form-control" placeholder="Enter basic salary" step="0.01" required value="{{ old('basic_salary') }}">
                                @error('basic_salary')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">HRA (House Rent Allowance)</label>
                            <div class="col-md-7">
                                <input type="number" name="hra" class="form-control" placeholder="Enter HRA" step="0.01" value="{{ old('hra', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">DA (Dearness Allowance)</label>
                            <div class="col-md-7">
                                <input type="number" name="da" class="form-control" placeholder="Enter DA" step="0.01" value="{{ old('da', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">TA (Travel Allowance)</label>
                            <div class="col-md-7">
                                <input type="number" name="ta" class="form-control" placeholder="Enter TA" step="0.01" value="{{ old('ta', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Medical Allowance</label>
                            <div class="col-md-7">
                                <input type="number" name="medical_allowance" class="form-control" placeholder="Enter medical allowance" step="0.01" value="{{ old('medical_allowance', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Other Allowances</label>
                            <div class="col-md-7">
                                <input type="number" name="other_allowances" class="form-control" placeholder="Enter other allowances" step="0.01" value="{{ old('other_allowances', 0) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Deductions</h6>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">PF (Provident Fund)</label>
                            <div class="col-md-7">
                                <input type="number" name="pf" class="form-control" placeholder="Enter PF deduction" step="0.01" value="{{ old('pf', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">ESI (Emp State Insurance)</label>
                            <div class="col-md-7">
                                <input type="number" name="esi" class="form-control" placeholder="Enter ESI deduction" step="0.01" value="{{ old('esi', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">TDS (Tax Deducted at Source)</label>
                            <div class="col-md-7">
                                <input type="number" name="tds" class="form-control" placeholder="Enter TDS" step="0.01" value="{{ old('tds', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Other Deductions</label>
                            <div class="col-md-7">
                                <input type="number" name="other_deductions" class="form-control" placeholder="Enter other deductions" step="0.01" value="{{ old('other_deductions', 0) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Gross Salary</label>
                            <div class="col-md-7">
                                <input type="number" id="gross_salary" class="form-control" placeholder="Auto-calculated" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Net Salary</label>
                            <div class="col-md-7">
                                <input type="number" id="net_salary" class="form-control" placeholder="Auto-calculated" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Effective Date</label>
                            <div class="col-md-7">
                                <input type="date" name="effective_date" class="form-control" value="{{ old('effective_date') }}">
                            </div>
                        </div>
                        <input type="hidden" name="currency" value="INR">
                        <div class="form-group row">
                            <label class="col-md-5 form-label">Status</label>
                            <div class="col-md-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employee-management.salary-structure.index', $employee->user->uuid) }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Save Salary Structure
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = ['basic_salary', 'hra', 'da', 'ta', 'medical_allowance', 'other_allowances', 'pf', 'esi', 'tds', 'other_deductions'];
    
    function calculateSalary() {
        // Calculate gross salary (basic + all allowances)
        const basicSalary = parseFloat(document.querySelector('[name="basic_salary"]').value) || 0;
        const hra = parseFloat(document.querySelector('[name="hra"]').value) || 0;
        const da = parseFloat(document.querySelector('[name="da"]').value) || 0;
        const ta = parseFloat(document.querySelector('[name="ta"]').value) || 0;
        const medicalAllowance = parseFloat(document.querySelector('[name="medical_allowance"]').value) || 0;
        const otherAllowances = parseFloat(document.querySelector('[name="other_allowances"]').value) || 0;
        
        const grossSalary = basicSalary + hra + da + ta + medicalAllowance + otherAllowances;
        
        // Calculate net salary (gross - all deductions)
        const pf = parseFloat(document.querySelector('[name="pf"]').value) || 0;
        const esi = parseFloat(document.querySelector('[name="esi"]').value) || 0;
        const tds = parseFloat(document.querySelector('[name="tds"]').value) || 0;
        const otherDeductions = parseFloat(document.querySelector('[name="other_deductions"]').value) || 0;
        
        const netSalary = grossSalary - pf - esi - tds - otherDeductions;
        
        document.getElementById('gross_salary').value = grossSalary.toFixed(2);
        document.getElementById('net_salary').value = netSalary.toFixed(2);
    }
    
    inputs.forEach(inputName => {
        const input = document.querySelector(`[name="${inputName}"]`);
        if (input) {
            input.addEventListener('input', calculateSalary);
            input.addEventListener('change', calculateSalary);
        }
    });
    
    // Calculate on page load
    calculateSalary();
});
</script>
@endsection

