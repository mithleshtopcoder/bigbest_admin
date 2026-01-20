@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Dashboard</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center gap-2" id="storeFilterForm">
                <label for="store_id" class="form-label mb-0 px-2">Filter by Store:</label>
                <select name="store_id" id="store_id" class="form-select form-select-sm" style="width: auto; min-width: 200px;" onchange="document.getElementById('storeFilterForm').submit();">
                    <option value="">All Stores</option>
                    @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                    @endforeach
                </select>
            </form>
            <span class="text-muted small"><b>Today's Date:</b> {{ now()->format('l, F d, Y') }}</span>
        </div>
    </div>
    @if($selectedStore)
    <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Viewing data for:</strong> {{ $selectedStore->name }}
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary ms-2">Clear Filter</a>
    </div>
    @endif
</div>

<div class="main-body">
    <div class="row g-3 mb-4">
        <!-- Today's Sales -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Today's Sales</p>
                            <h3 class="mb-0">₹{{ number_format($todaySales, 2) }}</h3>
                            @if($yesterdaySales > 0)
                            @php
                            $change = (($todaySales - $yesterdaySales) / $yesterdaySales) * 100;
                            @endphp
                            <small class="text-{{ $change >= 0 ? 'success' : 'danger' }}">
                                <i class="bi bi-arrow-{{ $change >= 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($change), 1) }}% from yesterday
                            </small>
                            @endif
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-currency-rupee fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Orders -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Today's Orders</p>
                            <h3 class="mb-0">{{ number_format($todayOrders) }}</h3>
                            <div class="d-flex gap-2 mt-2">
                                <span class="badge bg-info">Online: {{ $todayOnlineOrders }}</span>
                                <span class="badge bg-success">POS: {{ $todayPosOrders }}</span>
                            </div>
                            @if(isset($yesterdayOrders) && $yesterdayOrders > 0)
                            @php
                            $orderChange = (($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100;
                            @endphp
                            <small class="text-{{ $orderChange >= 0 ? 'success' : 'danger' }}">
                                <i class="bi bi-arrow-{{ $orderChange >= 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($orderChange), 1) }}%
                            </small>
                            @endif
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-cart-check fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Active Orders</p>
                            <h3 class="mb-0">{{ number_format($activeOrders) }}</h3>
                            @if($pendingOrders > 0)
                            <small class="text-warning">
                                <i class="bi bi-clock"></i> {{ $pendingOrders }} pending
                            </small>
                            @endif
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Customers</p>
                            <h3 class="mb-0">{{ number_format($totalCustomers) }}</h3>
                            <small class="text-muted">Registered users</small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-people fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row Statistics -->
    <div class="row g-3 mb-4">
        <!-- Month Sales -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">This Month Sales</p>
                            <h3 class="mb-0">₹{{ number_format($monthSales, 2) }}</h3>
                            @if($lastMonthSales > 0)
                            @php
                            $monthChange = (($monthSales - $lastMonthSales) / $lastMonthSales) * 100;
                            @endphp
                            <small class="text-{{ $monthChange >= 0 ? 'success' : 'danger' }}">
                                <i class="bi bi-arrow-{{ $monthChange >= 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($monthChange), 1) }}% from last month
                            </small>
                            @endif
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-graph-up-arrow fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Products</p>
                            <h3 class="mb-0">{{ number_format($totalProducts) }}</h3>
                            @if($outOfStockCount > 0)
                            <small class="text-danger">
                                <i class="bi bi-exclamation-triangle"></i> {{ $outOfStockCount }} out of stock
                            </small>
                            @endif
                        </div>
                        <div class="bg-secondary bg-opacity-10 rounded p-3">
                            <i class="bi bi-box-seam fs-4 text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Stores -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Active Stores</p>
                            <h3 class="mb-0">{{ number_format($totalStores) }}</h3>
                            <small class="text-muted">Operational stores</small>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-shop fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Employees -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Active Employees</p>
                            <h3 class="mb-0">{{ number_format($totalEmployees) }}</h3>
                            <small class="text-muted">On payroll</small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-person-badge fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Sales Overview (Last 7 Days)</h5>
                        <select id="salesPeriod" class="form-select form-select-sm" style="width: auto;">
                            <option value="7">Last 7 Days</option>
                            <option value="30">Last 30 Days</option>
                            <option value="90">Last 3 Months</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row g-3 mb-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="{{ route('new-order.index', 'online') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Source</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('new-order.view', ['type' => $order->order_source, 'id' => $order->id]) }}" class="text-decoration-none">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->customer ? $order->customer->full_name : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->order_source === 'online' ? 'info' : 'success' }}">
                                            {{ strtoupper($order->order_source) }}
                                        </span>
                                    </td>
                                    <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                        $statusColors = [
                                        'pending' => 'warning',
                                        'accepted' => 'info',
                                        'preparing' => 'primary',
                                        'ready' => 'secondary',
                                        'out-for-delivery' => 'warning',
                                        'completed' => 'success',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger'
                                        ];
                                        $color = $statusColors[$order->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No recent orders</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Top Selling Products</h5>
                </div>
                <div class="card-body">
                    @forelse($topProducts as $index => $product)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="text-primary fw-bold">{{ $index + 1 }}</span>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0 fw-medium">{{ \Illuminate\Support\Str::limit($product->name, 20) }}</p>
                                <small class="text-muted">{{ $product->total_quantity }} sold</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-bold">₹{{ number_format($product->total_revenue, 0) }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info Row -->
    <div class="row g-3 mb-4">
        <!-- Low Stock Alerts -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle text-warning"></i> Low Stock Alerts
                    </h5>
                    <a href="{{ route('inventory-management.store-wise-stock') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Store</th>
                                    <th>Available</th>
                                    <th>Min Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $stock)
                                <tr>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->store_name }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $stock->quantity }}</span>
                                    </td>
                                    <td>{{ $stock->min_stock_level }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No low stock alerts</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Stores -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Top Performing Stores</h5>
                </div>
                <div class="card-body">
                    @if($storeId)
                    <div class="text-center py-4">
                        <i class="bi bi-info-circle text-muted fs-4 mb-2 d-block"></i>
                        <p class="text-muted mb-0">Top stores comparison is available when viewing all stores.</p>
                        <small class="text-muted">Clear the store filter to see this data.</small>
                    </div>
                    @else
                    @forelse($topStores as $index => $store)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-{{ $index === 0 ? 'primary' : 'secondary' }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="text-{{ $index === 0 ? 'primary' : 'secondary' }} fw-bold">{{ $index + 1 }}</span>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0 fw-medium">{{ $store->name }}</p>
                                <small class="text-muted">{{ $store->total_orders }} orders</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-bold text-primary">₹{{ number_format($store->total_sales, 0) }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">No data available</p>
                    @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    @if(isset($recentActivities) && $recentActivities->count() > 0)
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Activities</h5>
                    <a href="{{ route('logs-audit.user-logs') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    @php
                                    $actionIcons = [
                                    'created' => 'bi-plus-circle text-success',
                                    'updated' => 'bi-pencil text-primary',
                                    'deleted' => 'bi-trash text-danger',
                                    'viewed' => 'bi-eye text-info'
                                    ];
                                    $icon = $actionIcons[$activity->action] ?? 'bi-circle text-secondary';
                                    @endphp
                                    <i class="bi {{ $icon }} fs-5"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="mb-1">
                                                <strong>{{ $activity->user ? $activity->user->name : 'System' }}</strong>
                                                <span class="text-capitalize">{{ $activity->action }}</span>
                                                <strong>{{ $activity->module }}</strong>
                                            </p>
                                            @if($activity->details)
                                            <small class="text-muted">{{ $activity->details }}</small>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    @endif
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    $(document).ready(function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            const salesChart = new Chart(salesCtx, {
                type: 'line'
                , data: {
                    labels: @json($salesChartLabels)
                    , datasets: [{
                        label: 'Sales (₹)'
                        , data: @json($salesChartData)
                        , borderColor: 'rgb(99, 102, 241)'
                        , backgroundColor: 'rgba(99, 102, 241, 0.1)'
                        , tension: 0.4
                        , fill: true
                        , pointRadius: 4
                        , pointHoverRadius: 6
                    }]
                }
                , options: {
                    responsive: true
                    , maintainAspectRatio: false
                    , plugins: {
                        legend: {
                            display: false
                        }
                        , tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₹' + context.parsed.y.toLocaleString('en-IN');
                                }
                            }
                        }
                    }
                    , scales: {
                        y: {
                            beginAtZero: true
                            , ticks: {
                                callback: function(value) {
                                    if (value >= 100000) {
                                        return '₹' + (value / 100000).toFixed(1) + 'L';
                                    } else if (value >= 1000) {
                                        return '₹' + (value / 1000).toFixed(1) + 'k';
                                    }
                                    return '₹' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Order Status Chart
        const statusCtx = document.getElementById('orderStatusChart');
        if (statusCtx) {
            const statusData = @json($orderStatusData);
            const labels = Object.keys(statusData);
            const data = Object.values(statusData);
            const colors = [
                'rgba(99, 102, 241, 0.8)'
                , 'rgba(34, 197, 94, 0.8)'
                , 'rgba(251, 191, 36, 0.8)'
                , 'rgba(239, 68, 68, 0.8)'
                , 'rgba(168, 85, 247, 0.8)'
                , 'rgba(236, 72, 153, 0.8)'
                , 'rgba(59, 130, 246, 0.8)'
            ];

            const orderStatusChart = new Chart(statusCtx, {
                type: 'doughnut'
                , data: {
                    labels: labels.map(label => label.charAt(0).toUpperCase() + label.slice(1))
                    , datasets: [{
                        data: data
                        , backgroundColor: colors.slice(0, labels.length)
                        , borderWidth: 2
                        , borderColor: '#fff'
                    }]
                }
                , options: {
                    responsive: true
                    , maintainAspectRatio: false
                    , plugins: {
                        legend: {
                            position: 'bottom'
                            , labels: {
                                padding: 15
                                , usePointStyle: true
                            }
                        }
                        , tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });

</script>
@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    #salesChart,
    #orderStatusChart {
        max-height: 300px;
    }

</style>
@endsection
