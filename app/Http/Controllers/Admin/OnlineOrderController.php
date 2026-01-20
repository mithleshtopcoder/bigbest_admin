<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnlineOrderController extends Controller
{
    public function newOrders()
    {
        return view('admin.online-orders.new');
    }

    public function accepted()
    {
        return view('admin.online-orders.accepted');
    }

    public function preparing()
    {
        return view('admin.online-orders.preparing');
    }

    public function outForDelivery()
    {
        return view('admin.online-orders.out-for-delivery');
    }

    public function completed()
    {
        return view('admin.online-orders.completed');
    }

    public function cancelled()
    {
        return view('admin.online-orders.cancelled');
    }

    public function assignment()
    {
        return view('admin.online-orders.assignment');
    }
}

