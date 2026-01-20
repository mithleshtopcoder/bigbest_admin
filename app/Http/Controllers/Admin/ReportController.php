<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport()
    {
        return view('reports-analytics.sales-reports.index');
    }
    
    public function pnl()
    {
        return view('admin.reports.pnl');
    }
    
    public function gstReport()
    {
        return view('admin.reports.gst-report');
    }
    
    public function inventoryValuation()
    {
        return view('admin.reports.inventory-valuation');
    }

    public function posReport()
    {
        return view('reports-analytics.pos-reports.index');
    }

    public function appOrdersReport()
    {
        return view('reports-analytics.app-orders-reports.index');
    }

    public function inventoryReport()
    {
        return view('reports-analytics.inventory-report.index');
    }

    public function taxReport()
    {
        return view('admin.reports.tax-report');
    }

    public function topSelling()
    {
        return view('reports-analytics.top-selling-products.index');
    }

    public function lowSelling()
    {
        return view('reports-analytics.low-selling-products.index');
    }

    public function expenseReport()
    {
        return view('reports-analytics.expense-report.index');
    }

    public function employeeSales()
    {
        return view('reports-analytics.employee-sales-report.index');
    }
}
