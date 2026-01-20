<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function userActivity()
    {
        return view('admin.logs.user-activity');
    }

    public function posActivity()
    {
        return view('admin.logs.pos-activity');
    }

    public function stockAdjustment()
    {
        return view('admin.logs.stock-adjustment');
    }

    public function orderHistory()
    {
        return view('admin.logs.order-history');
    }

    public function loginHistory()
    {
        return view('admin.logs.login-history');
    }
}

