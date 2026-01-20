@extends('layouts.app')

@section('title', 'View POS Order')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">View POS Order</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('new-order.index', 'pos') }}" class="text-decoration-none">POS Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('new-order.index', 'pos') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
            <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Print
            </button>
        </div>
    </div>
</div>
<div class="main-body">
    <div class="row">
        <!-- [View POS Order] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="row pl-2">
                        <div class="col-lg-8">
                            <!-- Customer Information -->
                            <div class="card-header py-2 mb-2 pl-1">
                                <h5 class="card-title mb-0">Customer Information</h5>
                            </div>
                            <div class="form-group row">
                                <label class="form-label text-md col-md-2 custom-label">Customer Name</label>
                                <div class="col-md-4 pl-1">
                                    <input type="text" class="form-control form-control-sm" value="John Doe" readonly>
                                </div>
                                <label class="form-label text-md col-md-2 custom-label">Customer Phone</label>
                                <div class="col-md-4 pl-1">
                                    <input type="text" class="form-control form-control-sm" value="+91 98765 43210" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pl-1 mb-4">
                                    <div class="mb-3 mt-3">
                                        <label class="form-label">Order Items</label>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="product_table">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th style="text-align: right">Price</th>
                                                        <th style="text-align: right;width: 90px;">Quantity</th>
                                                        <th style="text-align: right">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product_tbody">
                                                    <tr>
                                                        <td>
                                                            <strong>Gold Ring 22K</strong><br>
                                                            <small class="text-muted">SKU: GR-22K-001</small>
                                                        </td>
                                                        <td style="text-align: right">₹15,600</td>
                                                        <td style="text-align: right">2</td>
                                                        <td style="text-align: right">₹31,200</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Silver Bracelet</strong><br>
                                                            <small class="text-muted">SKU: SB-001</small>
                                                        </td>
                                                        <td style="text-align: right">₹8,500</td>
                                                        <td style="text-align: right">1</td>
                                                        <td style="text-align: right">₹8,500</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Gold Chain 24K</strong><br>
                                                            <small class="text-muted">SKU: GC-24K-002</small>
                                                        </td>
                                                        <td style="text-align: right">₹19,300</td>
                                                        <td style="text-align: right">1</td>
                                                        <td style="text-align: right">₹19,300</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <h5 class="card-title mb-0 mb-2">Order Notes</h5>
                                    <p class="mb-0 text-muted mb-4">
                                        Customer requested gift wrapping. Delivery scheduled for tomorrow.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <!-- Order Summary -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Subtotal</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹59,000.00" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Discount</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹500.00" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Tax (GST 18%)</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹10,530.00" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Other Charges</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹0.00" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="fs-5 fw-bold">Total</span>
                                        <span class="fs-5 fw-bold text-primary">₹69,030.00</span>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Payment Method</label>
                                        <div class="col-md-6 pl-1">
                                            <span class="badge bg-soft-primary text-primary">Card</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Store</label>
                                        <div class="col-md-6 pl-1">
                                            <span>Main Store</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Order ID</label>
                                        <div class="col-md-6 pl-1">
                                            <span>#POS-001</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Order Date</label>
                                        <div class="col-md-6 pl-1">
                                            <span>2024-01-15 10:30 AM</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Status</label>
                                        <div class="col-md-6 pl-1">
                                            <span class="badge bg-soft-success text-success">Completed</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4 mb-4 d-flex justify-content-end">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                                                <i class="feather-printer me-2"></i>Print
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" onclick="downloadInvoice()">
                                                <i class="feather-download me-2"></i>Download
                                            </button>
                                            <button type="button" class="btn btn-light btn-sm" onclick="window.location.href='{{ route('new-order.index', 'pos') }}'">
                                                <i class="feather-x me-2"></i>Close
                                            </button>
                                        </div>
                                    </div>
                                    <script>
                                        function downloadInvoice() {
                                            // Implement your download logic here
                                            alert('Download functionality not implemented yet.');
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [View POS Order] end -->
    </div>
</div>
@endsection

@section('scripts')
@endsection

@section('styles')
<style>
    @media print {
        .page-header,
        .card-header-action,
        .btn,
        .breadcrumb {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

