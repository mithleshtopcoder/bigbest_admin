<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sales()
    {
        return view('store.reports.sales');
    }

    public function dailySummary()
    {
        return view('store.reports.daily-summary');
    }

    public function employeeSales()
    {
        return view('store.reports.employee-sales');
    }
}

