@php
$statusRoutes = [
'all' => 'all',
'pending' => 'pending',
'accepted' => 'confirmed',
'preparing' => 'processing',
'shipped' => 'shipped',
'completed' => 'delivered',
'cancelled' => 'cancelled',
];

$statusescc = [['label' => 'New Order (POS)', 'count' => $newPosOrderCount ?? 0, 'icon' => 'bi bi-cart', 'status' => 'new-order-pos', 'color' => 'bg-warning text-white', 'db_status' => 'pending', 'route' => 'new-order.index', 'route_param' => 'pos'],];
$statuses = [
['label' => 'New Order (Online)', 'count' => $newOrderCount ?? 0, 'icon' => 'bi bi-plus-circle', 'status' => 'new-order-online', 'color' => 'bg-primary text-white', 'db_status' => 'pending', 'route' => 'new-order.index', 'route_param' => 'online'],
['label' => 'Accepted Order', 'count' => $statusCounts['confirmed'] ?? 0, 'icon' => 'bi bi-check-circle', 'status' => 'accepted', 'color' => 'bg-info text-white', 'db_status' => 'confirmed'],
['label' => 'Preparing Order', 'count' => $statusCounts['processing'] ?? 0, 'icon' => 'bi bi-hourglass-split', 'status' => 'preparing', 'color' => 'bg-secondary text-white', 'db_status' => 'processing'],
['label' => 'Ready To Ship', 'count' => $statusCounts['ready_to_ship'] ?? 0, 'icon' => 'bi bi-truck', 'status' => 'ready', 'color' => 'bg-primary text-white', 'db_status' => 'ready_to_ship'],
['label' => 'shipped', 'count' => $statusCounts['shipped'] ?? 0, 'icon' => 'bi bi-truck', 'status' => 'shipped', 'color' => 'bg-primary text-white', 'db_status' => 'shipped'],
['label' => 'Out for Delivery', 'count' => $statusCounts['out_for_delivery'] ?? 0, 'icon' => 'bi bi-box-seam', 'status' => 'out-for-delivery', 'color' => 'bg-success text-white', 'db_status' => 'out_for_delivery'],
['label' => 'Completed', 'count' => $statusCounts['delivered'] ?? 0, 'icon' => 'bi bi-check-circle-fill', 'status' => 'completed', 'color' => 'bg-success text-white', 'db_status' => 'delivered'],
['label' => 'Cancelled', 'count' => $statusCounts['cancelled'] ?? 0, 'icon' => 'bi bi-x-circle', 'status' => 'cancelled', 'color' => 'bg-danger text-white', 'db_status' => 'cancelled'],
];
@endphp

{{-- ['label' => 'Total Orders', 'count' => $totalOrders, 'icon' => 'feather-shopping-bag', 'status' => 'all', 'color' => 'bg-primary text-white'],
['label' => 'Total Amount', 'count' => '₹'.number_format($totalAmount, 2), 'icon' => 'feather-dollar-sign', 'status' => 'all', 'color' => 'bg-dark text-white'], --}}
<div class="row g-3 mb-4">
    @foreach($statuses as $stat)
    <div class="col">
        @php
        $isActive = false;
        if (isset($stat['route']) && $stat['route'] === 'new-order.index') {
        $isActive = request()->routeIs('new-order.index') && request()->route('type') === ($stat['route_param'] ?? null);
        } else {
        $isActive = $currentStatus === $stat['status'];
        }
        @endphp
        <a href="{{ isset($stat['route']) ? route($stat['route'], $stat['route_param'] ?? null) : route('order-status.index', $stat['status']) }}" class="dashboard-card {{ $isActive ? 'active' : '' }}">
            <div class="card h-100">
                <div class="card-body align-items-center p-2">
                    <!-- Icon Box -->
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-text avatar-lg {{ $stat['color'] }}">
                            <i class="{{ $stat['icon'] }} fs-5"></i>
                        </div>
                        <div class="fs-5 active-color fw-bold">{{ $stat['count'] }}</div>
                    </div>
                    <!-- Text -->
                    <div class="flex-grow-1 pt-1">
                        <div class="fs-13 active-color text-muted">{{ $stat['label'] }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<style>
    .dashboard-card {
        display: block;
        color: inherit;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dashboard-card .card {
            {
                {
                -- background-color: rgb(221 193 8 / 83%);
                --
            }
        }

        border-radius: 0.5rem;
    }

    .dashboard-card:hover {
        transform: translateY(-8px);
        /* box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); */
    }

    .dashboard-card.active .card {
        border-color: #ff0707f5 !important;
        border-width: 1px !important;
        box-shadow: 4px 4px 12px #ff0707b0 !important;
    }

    .dashboard-card.active:hover {
        transform: translateY(-3px);
    }

    .avatar-text {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        width: 30px;
        height: 30px;
    }

    .fs-13 {
        font-size: 0.8rem;
    }

    .card-body {
        padding: 1rem;
    }

    @media (max-width: 576px) {
        .avatar-text {
            width: 40px;
            height: 40px;
        }

        .fs-5 {
            font-size: 1rem;
        }
    }

</style>
