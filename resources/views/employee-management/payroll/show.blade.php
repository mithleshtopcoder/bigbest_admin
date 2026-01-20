@extends('layouts.app')

@section('title', 'Payroll Details - ' . $payroll->employeeProfile->user->name)

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Payroll Details</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.payroll') }}" class="text-decoration-none">Payroll</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.payroll.download-payslip', $payroll->id) }}" class="btn btn-sm btn-primary" target="_blank">
                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
            </a>
            <a href="{{ route('employee-management.payroll.view-payslip', $payroll->id) }}" class="btn btn-sm btn-info" target="_blank">
                <i class="bi bi-eye me-2"></i>View PDF
            </a>
            <button type="button" class="btn btn-sm btn-light" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Print
            </button>
            <a href="{{ route('employee-management.payroll') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Payslip - {{ $payroll->payroll_period }}</h5>
        </div>
        <div class="card-body">
            <!-- Employee Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Employee Information</h6>
                    <p class="mb-1"><strong>Name:</strong> {{ $payroll->employeeProfile->user->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Employee Code:</strong> {{ $payroll->employeeProfile->employee_code ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Department:</strong> {{ $payroll->employeeProfile->department->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Designation:</strong> {{ $payroll->employeeProfile->designation->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Payroll Information</h6>
                    <p class="mb-1"><strong>Period:</strong> {{ \Carbon\Carbon::parse($payroll->period_start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($payroll->period_end_date)->format('d M Y') }}</p>
                    <p class="mb-1"><strong>Status:</strong>
                        @if($payroll->status == 'draft')
                        <span class="badge bg-secondary">Draft</span>
                        @elseif($payroll->status == 'processed')
                        <span class="badge bg-info">Processed</span>
                        @elseif($payroll->status == 'approved')
                        <span class="badge bg-primary">Approved</span>
                        @elseif($payroll->status == 'paid')
                        <span class="badge bg-success">Paid</span>
                        @else
                        <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </p>
                    @if($payroll->payment_date)
                    <p class="mb-1"><strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($payroll->payment_date)->format('d M Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6 class="text-muted mb-3">Attendance Summary</h6>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="mb-0">{{ $payroll->working_days }}</h5>
                                    <small class="text-muted">Working Days</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="mb-0 text-success">{{ $payroll->present_days }}</h5>
                                    <small class="text-muted">Present Days</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="mb-0 text-danger">{{ $payroll->absent_days }}</h5>
                                    <small class="text-muted">Absent Days</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="mb-0 text-info">{{ $payroll->leave_days }}</h5>
                                    <small class="text-muted">Leave Days</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-secondary bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="mb-0">{{ $payroll->holiday_days }}</h5>
                                    <small class="text-muted">Holiday Days</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-secondary bg-opacity-10">
                                <div class="card-body text-center">
                                    <h5 class="mb-0">{{ $payroll->weekend_days }}</h5>
                                    <small class="text-muted">Weekend Days</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Earnings</h6>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Basic Salary</td>
                                <td class="text-end">{{ \App\Helpers\MyHelper::formatCurrency($payroll->basic_salary) }}</td>
                            </tr>
                            @foreach($payroll->items->where('item_type', 'allowance') as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td class="text-end">{{ \App\Helpers\MyHelper::formatCurrency($item->amount) }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-success">
                                <td><strong>Gross Salary</strong></td>
                                <td class="text-end"><strong>{{ \App\Helpers\MyHelper::formatCurrency($payroll->gross_salary) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Deductions</h6>
                    <table class="table table-bordered">
                        <tbody>
                            @foreach($payroll->items->where('item_type', 'deduction') as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td class="text-end text-danger">- {{ \App\Helpers\MyHelper::formatCurrency($item->amount) }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-danger">
                                <td><strong>Total Deductions</strong></td>
                                <td class="text-end"><strong>- {{ \App\Helpers\MyHelper::formatCurrency($payroll->total_deductions) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Net Salary -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card bg-primary bg-opacity-10">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-0">Net Salary</h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h3 class="mb-0 text-primary">{{ \App\Helpers\MyHelper::formatCurrency($payroll->net_salary) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($payroll->remarks)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6 class="text-muted">Remarks</h6>
                    <p>{{ $payroll->remarks }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {

        .page-header,
        .btn,
        .card-header .btn {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }

</style>
@endsection
