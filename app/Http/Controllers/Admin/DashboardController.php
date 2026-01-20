<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Store;
use App\Models\EmployeeProfile;
use App\Models\ProductStock;
use App\Models\OrderItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        /*
        |--------------------------------------------------------------------------
        | ACCESSIBLE STORES
        |--------------------------------------------------------------------------
        */
        if ($isSuperAdmin) {
            $stores = Store::where('status', 'active')
                ->orderBy('name')
                ->get();
        } else {
            $storeIds = collect();

            if ($user->store_id) {
                $storeIds->push($user->store_id);
            }

            $pivotStoreIds = $user->stores()->pluck('stores.id');

            $storeIds = $storeIds
                ->merge($pivotStoreIds)
                ->unique()
                ->values();

            $stores = Store::whereIn('id', $storeIds)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | STORE FILTER (AUTO SELECT USER STORE)
        |--------------------------------------------------------------------------
        */
        $storeId = $request->get('store_id');

        if (!$storeId && !$isSuperAdmin && $user->store_id) {
            $storeId = $user->store_id;
        }

        // Security: block unauthorized store access
        if ($storeId && !$stores->contains('id', $storeId)) {
            abort(403, 'Unauthorized store access');
        }

        /*
        |--------------------------------------------------------------------------
        | DATE RANGES
        |--------------------------------------------------------------------------
        */
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $last7Days = Carbon::now()->subDays(7);
        $last30Days = Carbon::now()->subDays(30);

        /*
        |--------------------------------------------------------------------------
        | STORE FILTER HELPER
        |--------------------------------------------------------------------------
        */
        $applyStoreFilter = function ($query) use ($storeId) {
            if ($storeId) {
                return $query->where('store_id', $storeId);
            }
            return $query;
        };

        /*
        |--------------------------------------------------------------------------
        | TODAY STATS
        |--------------------------------------------------------------------------
        */
        $todaySales = $applyStoreFilter(
            Order::whereDate('created_at', $today)
                ->whereIn('status', ['completed', 'delivered'])
        )->sum('total_amount');

        $todayOrders = $applyStoreFilter(
            Order::whereDate('created_at', $today)
        )->count();

        $todayOnlineOrders = $applyStoreFilter(
            Order::whereDate('created_at', $today)
                ->where('order_source', 'online')
        )->count();

        $todayPosOrders = $applyStoreFilter(
            Order::whereDate('created_at', $today)
                ->where('order_source', 'pos')
        )->count();

        /*
        |--------------------------------------------------------------------------
        | YESTERDAY STATS
        |--------------------------------------------------------------------------
        */
        $yesterdayOrders = $applyStoreFilter(
            Order::whereDate('created_at', $yesterday)
        )->count();

        $yesterdaySales = $applyStoreFilter(
            Order::whereDate('created_at', $yesterday)
                ->whereIn('status', ['completed', 'delivered'])
        )->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | MONTH STATS
        |--------------------------------------------------------------------------
        */
        $monthSales = $applyStoreFilter(
            Order::whereBetween('created_at', [$thisMonth, Carbon::now()])
                ->whereIn('status', ['completed', 'delivered'])
        )->sum('total_amount');

        $monthOrders = $applyStoreFilter(
            Order::whereBetween('created_at', [$thisMonth, Carbon::now()])
        )->count();

        $lastMonthSales = $applyStoreFilter(
            Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->whereIn('status', ['completed', 'delivered'])
        )->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | OVERALL STATS
        |--------------------------------------------------------------------------
        */
        $totalSales = $applyStoreFilter(
            Order::whereIn('status', ['completed', 'delivered'])
        )->sum('total_amount');

        $totalOrders = $applyStoreFilter(Order::query())->count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalStores = Store::where('status', 'active')->count();

        $totalEmployeesQuery = EmployeeProfile::whereHas('user', function ($q) {
            $q->where('status', 1);
        })->where('resign_status', 0);

        if ($storeId) {
            $totalEmployeesQuery->where('store_id', $storeId);
        }

        $totalEmployees = $totalEmployeesQuery->count();

        /*
        |--------------------------------------------------------------------------
        | ORDER STATUS
        |--------------------------------------------------------------------------
        */
        $activeOrders = $applyStoreFilter(
            Order::whereIn('status', ['accepted', 'preparing', 'ready', 'out-for-delivery'])
        )->count();

        $pendingOrders = $applyStoreFilter(
            Order::where('status', 'pending')
        )->count();

        /*
        |--------------------------------------------------------------------------
        | GROWTH
        |--------------------------------------------------------------------------
        */
        $salesGrowth = $lastMonthSales > 0
            ? (($monthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        $orderGrowth = $yesterdayOrders > 0
            ? (($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | SALES CHART (7 DAYS)
        |--------------------------------------------------------------------------
        */
        $salesChartData = [];
        $salesChartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $salesChartLabels[] = $date->format('D');

            $salesChartData[] = $applyStoreFilter(
                Order::whereDate('created_at', $date)
                    ->whereIn('status', ['completed', 'delivered'])
            )->sum('total_amount');
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER STATUS DISTRIBUTION
        |--------------------------------------------------------------------------
        */
        $orderStatusQuery = Order::select('status', DB::raw('count(*) as count'));
        if ($storeId) {
            $orderStatusQuery->where('store_id', $storeId);
        }

        $orderStatusData = $orderStatusQuery
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTS (30 DAYS)
        |--------------------------------------------------------------------------
        */
        $topProductsQuery = OrderItem::select(
            'product_variants.product_id',
            'products.name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.total_price) as total_revenue')
        )
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$last30Days, Carbon::now()]);

        if ($storeId) {
            $topProductsQuery->where('orders.store_id', $storeId);
        }

        $topProducts = $topProductsQuery
            ->groupBy('product_variants.product_id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT ORDERS
        |--------------------------------------------------------------------------
        */
        $recentOrdersQuery = Order::with(['customer', 'store'])
            ->orderBy('created_at', 'desc');

        if ($storeId) {
            $recentOrdersQuery->where('store_id', $storeId);
        }

        $recentOrders = $recentOrdersQuery->limit(10)->get();

        /*
        |--------------------------------------------------------------------------
        | LOW & OUT OF STOCK
        |--------------------------------------------------------------------------
        */
        $lowStockQuery = ProductStock::select(
            'product_variants.product_id',
            'products.name',
            'product_stocks.store_id',
            'stores.name as store_name',
            'product_stocks.quantity',
            'product_stocks.min_stock_level'
        )
            ->join('product_variants', 'product_stocks.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('stores', 'product_stocks.store_id', '=', 'stores.id')
            ->whereColumn('product_stocks.quantity', '<=', 'product_stocks.min_stock_level')
            ->where('product_stocks.quantity', '>', 0);

        if ($storeId) {
            $lowStockQuery->where('product_stocks.store_id', $storeId);
        }

        $lowStockProducts = $lowStockQuery
            ->orderBy('product_stocks.quantity', 'asc')
            ->limit(10)
            ->get();

        $outOfStockQuery = ProductStock::where('quantity', 0);
        if ($storeId) {
            $outOfStockQuery->where('store_id', $storeId);
        }
        $outOfStockCount = $outOfStockQuery->count();

        /*
        |--------------------------------------------------------------------------
        | TOP STORES (ONLY FOR SUPER ADMIN)
        |--------------------------------------------------------------------------
        */
        $topStores = collect();

        if (!$storeId && $isSuperAdmin) {
            $topStores = Order::select(
                'stores.name',
                'stores.id',
                DB::raw('SUM(orders.total_amount) as total_sales'),
                DB::raw('COUNT(orders.id) as total_orders')
            )
                ->join('stores', 'orders.store_id', '=', 'stores.id')
                ->whereIn('orders.status', ['completed', 'delivered'])
                ->whereBetween('orders.created_at', [$last30Days, Carbon::now()])
                ->groupBy('stores.id', 'stores.name')
                ->orderBy('total_sales', 'desc')
                ->limit(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOGS
        |--------------------------------------------------------------------------
        */
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $selectedStore = $stores->firstWhere('id', $storeId);

        return view('home', compact(
            'todaySales',
            'yesterdaySales',
            'todayOrders',
            'yesterdayOrders',
            'todayOnlineOrders',
            'todayPosOrders',
            'monthSales',
            'lastMonthSales',
            'monthOrders',
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'totalStores',
            'totalEmployees',
            'activeOrders',
            'pendingOrders',
            'salesGrowth',
            'orderGrowth',
            'salesChartData',
            'salesChartLabels',
            'orderStatusData',
            'topProducts',
            'recentOrders',
            'lowStockProducts',
            'outOfStockCount',
            'topStores',
            'recentActivities',
            'stores',
            'storeId',
            'selectedStore'
        ));
    }
}