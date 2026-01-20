<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function goals()
    {
        return view('admin.hrm.performance.goals');
    }

    public function reviews()
    {
        return view('admin.hrm.performance.reviews');
    }

    public function reports()
    {
        return view('admin.hrm.performance.reports');
    }
}

